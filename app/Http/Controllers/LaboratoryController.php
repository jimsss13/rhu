<?php

namespace App\Http\Controllers;

use App\Mail\DemoMail;
use App\Models\Notification;
use App\Models\RhuAppointment;
use App\Models\RhuClient;
use App\Models\RhuSupply;
use App\Models\RhuTest;
use App\Models\RhuUser;
use App\Services\AppointmentRecordService;
use App\Services\ReceiptService;
use App\Services\SemaphoreService;
use App\Support\PesoAmount;
use App\Support\PhilippineMobileNumber;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Mail;

/**
 * Handles laboratory department operations: appointments, test management,
 * patient records, receipt generation, and email/SMS notifications.
 */
class LaboratoryController extends Controller
{
    use LogsActivity;

    protected $semaphoreService;

    protected $currentDepartment;

    protected $currentRole;

    public function __construct(SemaphoreService $semaphoreService)
    {
        $this->semaphoreService = $semaphoreService;
        $this->currentDepartment = session('currentDepartment');
        $this->currentRole = session('currentRole');
    }

    public function reports()
    {
        if (auth()->user()->department !== 'LABORATORY') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $patients = RhuClient::where('type', 'laboratory')->get();
        $blood_appointments = RhuAppointment::where('type', 'laboratory')->get();
        $tests = RhuTest::all();

        return view('laboratory.reports', compact('patients', 'blood_appointments', 'tests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:laboratory',
            'sub_type' => 'required|string|max:255',
            'date' => 'required|string',
        ]);

        try {
            $formattedDate = Carbon::createFromFormat('Y-m-d h:i A', $request->date)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid date format provided.');
        }

        $existingAppointment = RhuAppointment::where('client_id', Auth::id())
            ->where('type', $request->type)
            ->where('sub_type', $request->sub_type)
            ->whereIn('status', ['Pending', 'Approved'])
            ->exists();

        if ($existingAppointment) {
            return redirect()->back()->with('error', 'You have a pending appointment for '.$request->sub_type);
        }

        $client = RhuAppointment::create([
            'name' => trim(auth()->user()->f_name.' '.auth()->user()->l_name),
            'type' => $request->type,
            'sub_type' => $request->sub_type,
            'date' => $formattedDate,
            'client_id' => Auth::id(),
            'status' => 'Pending',
            'email' => auth()->user()->email,
            'contactNo' => auth()->user()->contactNo,
        ]);

        $notificationClient = RhuUser::where('department', $request->type)->first();

        if ($notificationClient) {
            Notification::create([
                'client_id' => $notificationClient->id,
                'content' => "New appointment for {$client->type} ({$client->sub_type}) has been created.",
                'status' => 'unread',
                'date' => now(),
            ]);
        }

        $this->logActivity("{$client->name} Added new appointment to laboratory - {$client->sub_type}.");

        return redirect()->back()->with('success', 'Appointment created successfully.');
    }

    public function laboratory()
    {
        if (auth()->user()->department !== 'LABORATORY') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $pendingCount = RhuAppointment::where('status', 'Pending')->where('type', 'laboratory')->count();
        $approvedCount = RhuAppointment::where('status', 'Approved')->where('type', 'laboratory')->count();
        $totalCount = $pendingCount + $approvedCount;
        $completedCount = RhuClient::where('status', 'Completed')->where('type', 'laboratory')->count();

        return view('laboratory.homepage', [
            'totalMedicines' => RhuSupply::where('type', 'Medicines')->count(),
            'totalSupplies' => RhuSupply::where('type', 'Medical Supplies')->count(),
            'totalEquipments' => RhuSupply::where('type', 'Medical Equipments')->count(),
            'totalVaccines' => RhuSupply::where('type', 'Vaccines')->count(),
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'totalCount' => $totalCount,
            'completedCount' => $completedCount,
            'currentDepartment' => $this->currentDepartment,
        ]);
    }

    public function display_lab_appointments()
    {
        if (auth()->user()->department !== 'LABORATORY') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $lab_appointments = RhuAppointment::where('type', 'laboratory')
            ->whereIn('status', ['Pending', 'Approved'])
            ->get();

        return view('laboratory.display_appointments', compact('lab_appointments'));
    }

    public function approve_appointments(Request $request, $id)
    {
        $client = RhuAppointment::findOrFail($id);
        Gate::authorize('manage-appointment', $client);

        try {
            $client->status = 'Approved';
            $client->save();

            // Use the client's actual contact number instead of hardcoded
            if ($client->contactNo) {
                $message = 'Your appointment has been approved.';

                try {
                    $this->semaphoreService->sendSMS(PhilippineMobileNumber::normalize($client->contactNo), $message);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('SMS failed: '.$e->getMessage());
                }
            }

            $this->logActivity("Approved {$client->name} appointment - {$client->sub_type}.");

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update_lab_appointments(Request $request, $pId)
    {
        $client = RhuAppointment::findOrFail($pId);
        Gate::authorize('manage-appointment', $client);

        $validatedData = $request->validate([
            'status' => 'required|string|in:Pending,Approved,Completed,Rejected',
        ]);

        $client->update($validatedData);

        $this->logActivity("Updated lab appointment for {$client->name}");

        session()->flash('success', 'Appointment updated successfully.');

        return redirect()->back();
    }

    public function email(Request $request, $id, ReceiptService $receipts)
    {
        $type = strtolower(auth()->user()->department);

        try {
            $appointment = RhuAppointment::where('type', $type)
                ->where('status', 'Approved')
                ->whereKey($id)
                ->first();

            if (! $appointment) {
                return redirect()->back()->with('error', 'Appointment not found.');
            }

            $testPrice = match ($appointment->type) {
                'consultation' => 0,
                'laboratory' => RhuTest::where('name', $appointment->sub_type)->value('price') ?? 0,
                'blood' => 1,
                'vaccination' => 2,
                default => 0,
            };

            $r_date = now()->format('m/d/Y');
            $r_no = random_int(100000, 999999);
            $r_series = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5));
            $r_agency = config('app.name', 'RURAL HEALTH OF LUCBAN');
            $r_payor = $appointment->name;
            $r_nature = $appointment->sub_type.' '.$appointment->type;
            $r_acc_code = random_int(100000, 999999);
            $r_amount = $testPrice;
            $r_total = $testPrice;

            $r_amount_word = PesoAmount::inWords($r_total);

            $r_teller = config('services.receipt.teller', 'Teller Name');
            $r_officer = config('services.receipt.officer', 'Collecting Officer');

            $filePath = $receipts->generate([
                'r_date' => $r_date, 'r_no' => $r_no, 'r_series' => $r_series,
                'r_agency' => $r_agency, 'r_payor' => $r_payor, 'r_nature' => $r_nature,
                'r_acc_code' => $r_acc_code, 'r_amount' => number_format($r_amount, 2),
                'r_total' => number_format($r_total, 2), 'r_amount_word' => $r_amount_word,
                'r_teller' => $r_teller, 'r_officer' => $r_officer,
            ]);

            $mailData = [
                'title' => 'Rural Health Unit',
                'client' => "{$appointment->name}",
                'body' => "Your appointment for {$type} {$appointment->sub_type} services has been approved. Please arrive at least 15 minutes early and bring any necessary documents.",
                'file_path' => $filePath,
            ];

            $client_email = RhuUser::where('id', $appointment->client_id)->first();

            if ($client_email) {
                try {
                    Mail::to($client_email->email)->send(new DemoMail($mailData));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Email failed: '.$e->getMessage());
                }

                Notification::create([
                    'client_id' => $appointment->client_id,
                    'content' => "Your appointment for {$type} {$appointment->sub_type} services has been approved.",
                    'status' => 'unread',
                    'date' => now(),
                ]);
            }

            // SMS notification
            if ($appointment->contactNo) {
                try {
                    $formattedContactNo = PhilippineMobileNumber::normalize($appointment->contactNo);
                    Http::withHeaders([
                        'Authorization' => 'Bearer '.config('services.philsms.token'),
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ])->post(config('services.philsms.url'), [
                        'recipient' => $formattedContactNo,
                        'sender_id' => 'PhilSMS',
                        'type' => 'plain',
                        'message' => 'Good day! Your appointment for '.strtoupper($appointment->type)." {$appointment->sub_type} services has been approved.\nPlease arrive at least 15 minutes early.",
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('SMS service error: '.$e->getMessage());
                }
            }

            return redirect()->back();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error generating PDF: '.$e->getMessage());

            return redirect()->back()->with('error', 'An error occurred while generating the PDF.');
        }
    }

    public function delete_appointments($id)
    {
        $client = RhuAppointment::findOrFail($id);
        Gate::authorize('manage-appointment', $client);

        try {
            $client->delete();

            session()->flash('success', 'Appointment deleted successfully.');

            $this->logActivity("Deleted {$client->name} appointment - {$client->sub_type}.");

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function lab_staff_management()
    {
        if (auth()->user()->department !== 'LABORATORY') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $staffs = RhuUser::where('role', 'Admin')
            ->where('department', 'LABORATORY')
            ->get()
            ->map(function ($staff) {
                if ($staff->bday) {
                    $staff->bday = Carbon::parse($staff->bday)->format('Y-m-d');
                }

                return $staff;
            })
            ->sortBy('created_at');

        return view('laboratory.staff_account', compact('staffs'));
    }

    // ── Lab Tests ──────────────────────────────────────────────

    public function lab_tests()
    {
        if (auth()->user()->department !== 'LABORATORY') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $lab_tests = RhuTest::all();

        return view('laboratory.lab_tests', compact('lab_tests'));
    }

    public function add_lab_tests(Request $request)
    {
        $validatedAttributes = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rhu_test')->ignore($request->id),
            ],
            'price' => 'nullable|string',
            'status' => 'nullable|string|max:255',
        ]);

        $x = RhuTest::create($validatedAttributes);

        $this->logActivity("Added lab test {$x->name}.");

        return redirect()->back()->with('success', 'Test Added Successfully.');
    }

    public function update_lab_tests(Request $request, $pId)
    {
        $lab_test = RhuTest::findOrFail($pId);

        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rhu_test')->ignore($lab_test->id),
            ],
            'price' => 'nullable|string',
            'status' => 'nullable|string|max:255',
        ]);

        $lab_test->update($validatedData);

        $this->logActivity("Updated lab test {$lab_test->name}.");

        session()->flash('success', 'Lab test information updated successfully.');

        return redirect()->back();
    }

    public function delete_lab_tests($id)
    {
        try {
            $lab_test = RhuTest::findOrFail($id);
            $lab_test->delete();

            session()->flash('success', 'Lab test deleted successfully.');

            $this->logActivity("Deleted lab test {$lab_test->name}.");

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Patient Records ──────────────────────────────────────────

    public function lab_patient_records()
    {
        if (auth()->user()->department !== 'LABORATORY') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $patients = RhuClient::where('type', 'laboratory')
            ->whereIn('status', ['Completed', 'Rejected'])
            ->get();

        $appointments = RhuAppointment::where('type', 'laboratory')
            ->where('status', 'Approved')
            ->get();

        return view('laboratory.lab_patient_records', compact('patients', 'appointments'));
    }

    public function add_lab_patient_records(Request $request, AppointmentRecordService $records)
    {
        $attributes = $request->validate([
            'appointment_id' => 'required|integer',
            'result' => 'required|file|mimes:pdf,docx,jpg,png|max:2048',
            'status' => 'required|string|in:Completed,Rejected',
        ]);

        $client = $records->record((int) $attributes['appointment_id'], 'laboratory', $attributes);
        $this->logActivity("Added {$client->name} to laboratory patient records.");

        return redirect()->back()->with('success', 'Record added successfully!');
    }

    public function getSubType($name)
    {
        $appointment = RhuAppointment::where('name', $name)
            ->where('type', 'laboratory')
            ->first();

        if ($appointment) {
            return response()->json(['sub_type' => $appointment->sub_type]);
        }

        return response()->json(['error' => 'No data found'], 404);
    }

    public function delete_patient_records($id)
    {
        $clients = RhuClient::findOrFail($id);
        Gate::authorize('manage-record', $clients);

        try {
            $clients->delete();

            session()->flash('success', 'Patient Record deleted successfully.');

            $this->logActivity("Deleted {$clients->name} from patient record.");

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
