<?php

namespace App\Http\Controllers;

use App\Mail\DemoMail;
use App\Models\Notification;
use App\Models\RhuAppointment;
use App\Models\RhuClient;
use App\Models\RhuSupply;
use App\Models\RhuUser;
use App\Services\AppointmentRecordService;
use App\Support\PhilippineMobileNumber;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Mail;

/**
 * Handles consultation department operations: appointments, patient records,
 * referral/rejection with SMS and email notifications.
 */
class ConsultationController extends Controller
{
    use LogsActivity;

    protected $currentDepartment;

    protected $currentRole;

    public function __construct()
    {
        $this->currentDepartment = session('currentDepartment');
        $this->currentRole = session('currentRole');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:consultation',
            'sub_type' => 'required|string|max:255',
            'date' => 'required|string',
            'reason' => 'required|string|max:255',
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
            'reason' => $request->reason,
            'client_id' => Auth::id(),
            'status' => 'Pending',
            'email' => auth()->user()->email,
            'contactNo' => auth()->user()->contactNo,
        ]);

        $this->logActivity("{$client->name} Added new appointment to Department: ".ucfirst($client->department ?? 'consultation')." - {$client->sub_type}.");

        $notificationClient = RhuUser::where('department', $request->type)->first();

        if ($notificationClient) {
            Notification::create([
                'client_id' => $notificationClient->id,
                'content' => "New appointment for {$client->type} ({$client->sub_type}) has been created.",
                'status' => 'unread',
                'date' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Appointment created successfully.');
    }

    public function consultation()
    {
        if (auth()->user()->department !== 'CONSULTATION') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $pendingCount = RhuAppointment::where('status', 'Pending')->where('type', 'consultation')->count();
        $approvedCount = RhuAppointment::where('status', 'Approved')->where('type', 'consultation')->count();
        $totalCount = $pendingCount + $approvedCount;
        $completedCount = RhuClient::where('status', 'Completed')->where('type', 'consultation')->count();

        return view('consultation.homepage', [
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

    public function display_con_appointments()
    {
        if (auth()->user()->department !== 'CONSULTATION') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $con_appointments = RhuAppointment::where('type', 'consultation')
            ->whereIn('status', ['Pending', 'Approved', 'Rejected'])
            ->get();

        return view('consultation.display_appointments', compact('con_appointments'));
    }

    public function con_refer_reject(Request $request, $pId)
    {
        $client = RhuAppointment::findOrFail($pId);
        Gate::authorize('manage-appointment', $client);

        $validatedData = $request->validate([
            'refer' => 'nullable|string|max:255',
        ]);

        $validatedData['status'] = 'Rejected';
        $client->update($validatedData);

        $client_email = RhuUser::where('id', $client->client_id)->first();

        if ($client_email) {
            $userEmail = $client_email->email;

            $mailData = [
                'title' => 'Rural Health Unit',
                'client' => "{$client->name}",
                'body' => "Your appointment for {$client->sub_type} consultation has been rejected this time due to some reasons. We apologize for the inconvenience. As an alternative, we are referring you to the following healthcare provider: {$client->refer}. Please reach out to them directly to book an appointment.",
            ];

            try {
                Mail::to($userEmail)->send(new DemoMail($mailData));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send rejection email: '.$e->getMessage());
            }
        }

        $this->logActivity("Rejected {$client->name} appointment - {$client->sub_type}.");

        // SMS notification
        try {
            $formattedContactNo = PhilippineMobileNumber::normalize($client->contactNo ?? '');
            $smsResponse = Http::withHeaders([
                'Authorization' => 'Bearer '.config('services.philsms.token'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post(config('services.philsms.url'), [
                'recipient' => $formattedContactNo,
                'sender_id' => 'PhilSMS',
                'type' => 'plain',
                'message' => 'Your appointment for '.strtoupper($client->type)." {$client->sub_type} consultation has been rejected.\n\nWe are referring you to: {$client->refer}\n\nPlease reach out to them directly.",
            ]);

            if ($smsResponse->successful()) {
                \Illuminate\Support\Facades\Log::info('SMS sent successfully to '.$client->contactNo);
            } else {
                \Illuminate\Support\Facades\Log::error('SMS sending failed: '.$smsResponse->body());
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMS service error: '.$e->getMessage());
        }

        return redirect()->back();
    }

    public function reports()
    {
        if (auth()->user()->department !== 'CONSULTATION') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $patients = RhuClient::where('type', 'consultation')->get();
        $blood_appointments = RhuAppointment::where('type', 'consultation')->get();

        return view('consultation.reports', compact('patients', 'blood_appointments'));
    }

    public function con_staff_management()
    {
        if (auth()->user()->department !== 'CONSULTATION') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $staffs = RhuUser::where('role', 'Admin')
            ->where('department', 'CONSULTATION')
            ->get()
            ->map(function ($staff) {
                if ($staff->bday) {
                    $staff->bday = Carbon::parse($staff->bday)->format('Y-m-d');
                }

                return $staff;
            })
            ->sortBy('created_at');

        return view('consultation.staff_account', compact('staffs'));
    }

    public function con_patient_records()
    {
        if (auth()->user()->department !== 'CONSULTATION') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $patients = RhuClient::where('type', 'consultation')
            ->whereIn('status', ['Completed', 'Rejected'])
            ->get();

        $appointments = RhuAppointment::where('type', 'consultation')
            ->where('status', 'Approved')
            ->get();

        return view('consultation.lab_patient_records', compact('patients', 'appointments'));
    }

    public function add_con_patient_records(Request $request, AppointmentRecordService $records)
    {
        $attributes = $request->validate([
            'appointment_id' => 'required|integer',
            'doctor' => 'required|string|max:255',
            'analysis' => 'required|string|max:255',
            'status' => 'required|string|in:Completed,Rejected',
        ]);

        $client = $records->record((int) $attributes['appointment_id'], 'consultation', $attributes);
        $this->logActivity("Added {$client->name} to consultation patient records.");

        return redirect()->back()->with('success', 'Record added successfully!');
    }

    public function update_con_appointments(Request $request, $pId)
    {
        $client = RhuAppointment::findOrFail($pId);
        Gate::authorize('manage-appointment', $client);

        $validatedData = $request->validate([
            'status' => 'required|string|in:Pending,Approved,Completed,Rejected',
        ]);

        $client->update($validatedData);

        $this->logActivity("Updated consultation appointment for {$client->name}");

        session()->flash('success', 'Appointment updated successfully.');

        return redirect()->back();
    }

    public function getSubType($name)
    {
        $appointment = RhuAppointment::where('name', $name)
            ->where('type', 'consultation')
            ->first();

        if ($appointment) {
            return response()->json([
                'sub_type' => $appointment->sub_type,
                'reason' => $appointment->reason,
            ]);
        }

        return response()->json(['error' => 'No data found'], 404);
    }
}
