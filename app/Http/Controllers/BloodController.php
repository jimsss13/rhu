<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\RhuAppointment;
use App\Models\RhuBloodType;
use App\Models\RhuClient;
use App\Models\RhuMedicalHistory;
use App\Models\RhuSupply;
use App\Models\RhuTurnedOver;
use App\Models\RhuUser;
use App\Services\AppointmentRecordService;
use App\Services\BloodTurnoverService;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

/**
 * Handles blood donation department operations: appointments, patient records,
 * blood inventory tracking, and turned-over management.
 */
class BloodController extends Controller
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
            'type' => 'required|in:blood',
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

        // Medical history
        $validatedAttributes = $request->validate([
            'well_health' => 'required|string|max:255',
            'antibiotics' => 'required|string|max:255',
            'infection_medication' => 'nullable|string|max:255',
            'medication_deferral' => 'required|string|max:255',
            'aspirin' => 'required|string|max:255',
            'vaccinations' => 'nullable|string|max:255',
            'pregnant' => 'required|string|max:255',
            'donated_recently' => 'required|string|max:255',
            'apheresis' => 'nullable|string|max:255',
            'blood_transfusion' => 'required|string|max:255',
            'transplant' => 'required|string|max:255',
            'graft' => 'required|string|max:255',
            'contact_blood' => 'required|string|max:255',
            'needlestick_injury' => 'nullable|string|max:255',
            'sexual_contact_hiv' => 'required|string|max:255',
            'prostitute_contact' => 'required|string|max:255',
            'drug_use_contact' => 'nullable|string|max:255',
            'hemophilia_contact' => 'required|string|max:255',
            'male_contact_with_male' => 'required|string|max:255',
            'saliva_contact_hepatitis' => 'nullable|string|max:255',
            'contact_blood_hepatitis' => 'required|string|max:255',
            'sexual_contact_hepatitis' => 'required|string|max:255',
            'tattoo' => 'required|string|max:255',
            'piercing' => 'required|string|max:255',
            'acupuncture' => 'nullable|string|max:255',
            'syphilis_gonorrhea' => 'required|string|max:255',
            'juvenile_detention' => 'required|string|max:255',
            'hiv_aids_positive' => 'nullable|string|max:255',
            'used_needles' => 'required|string|max:255',
            'clotting_factor' => 'required|string|max:255',
            'hepatitis' => 'nullable|string|max:255',
            'malaria' => 'required|string|max:255',
            'chagas' => 'required|string|max:255',
            'babesiosis' => 'required|string|max:255',
        ]);

        $client = DB::transaction(function () use ($request, $formattedDate, $validatedAttributes) {
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

            $validatedAttributes['client_id'] = Auth::id();

            RhuMedicalHistory::updateOrCreate(
                ['client_id' => $validatedAttributes['client_id']],
                $validatedAttributes
            );

            return $client;
        });

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

    public function blood()
    {
        if (auth()->user()->department !== 'BLOOD') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $pendingCount = RhuAppointment::where('status', 'Pending')->where('type', 'blood')->count();
        $approvedCount = RhuAppointment::where('status', 'Approved')->where('type', 'blood')->count();
        $totalCount = $pendingCount + $approvedCount;
        $completedCount = RhuClient::where('status', 'Completed')->where('type', 'blood')->count();
        $bloodTypes = RhuBloodType::all();

        return view('blood.homepage', [
            'totalMedicines' => RhuSupply::where('type', 'Medicines')->count(),
            'totalSupplies' => RhuSupply::where('type', 'Medical Supplies')->count(),
            'totalEquipments' => RhuSupply::where('type', 'Medical Equipments')->count(),
            'totalVaccines' => RhuSupply::where('type', 'Vaccines')->count(),
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'totalCount' => $totalCount,
            'completedCount' => $completedCount,
            'currentDepartment' => $this->currentDepartment,
            'bloodTypes' => $bloodTypes,
        ]);
    }

    public function reports()
    {
        if (auth()->user()->department !== 'BLOOD') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $turned_overs = RhuTurnedOver::all();
        $patients = RhuClient::where('type', 'blood')->get();
        $blood_appointments = RhuAppointment::where('type', 'blood')->get();

        return view('blood.reports', compact('turned_overs', 'patients', 'blood_appointments'));
    }

    public function display_blood_appointments()
    {
        if (auth()->user()->department !== 'BLOOD') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $blood_appointments = RhuAppointment::where('type', 'blood')
            ->whereIn('status', ['Pending', 'Approved'])
            ->get();
        $medical_history = RhuMedicalHistory::all();

        return view('blood.display_appointments', compact('blood_appointments', 'medical_history'));
    }

    public function blood_staff_management()
    {
        if (auth()->user()->department !== 'BLOOD') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $staffs = RhuUser::where('role', 'Admin')
            ->where('department', 'BLOOD')
            ->get()
            ->map(function ($staff) {
                if ($staff->bday) {
                    $staff->bday = Carbon::parse($staff->bday)->format('Y-m-d');
                }

                return $staff;
            })
            ->sortBy('created_at');

        return view('blood.staff_account', compact('staffs'));
    }

    public function blood_patient_records()
    {
        if (auth()->user()->department !== 'BLOOD') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $patients = RhuClient::where('type', 'blood')
            ->whereIn('status', ['Completed', 'Rejected'])
            ->get();

        $appointments = RhuAppointment::where('type', 'blood')
            ->where('status', 'Approved')
            ->get();

        return view('blood.lab_patient_records', compact('patients', 'appointments'));
    }

    public function add_blood_patient_records(Request $request, AppointmentRecordService $records)
    {
        $attributes = $request->validate([
            'appointment_id' => 'required|integer',
            'volume' => 'required|integer|min:1|max:99999',
            'status' => 'required|string|in:Completed,Rejected',
        ]);

        $client = $records->record((int) $attributes['appointment_id'], 'blood', $attributes);
        $this->logActivity("Added {$client->name} to blood patient records.");

        return redirect()->back()->with('success', 'Record added successfully!');
    }

    public function getSubType($name)
    {
        $appointment = RhuAppointment::where('name', $name)
            ->where('type', 'blood')
            ->first();

        if ($appointment) {
            return response()->json(['sub_type' => $appointment->sub_type]);
        }

        return response()->json(['error' => 'No data found'], 404);
    }

    public function turned_overs()
    {
        if (auth()->user()->department !== 'BLOOD') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $turned_overs = RhuTurnedOver::all();

        return view('blood.turned_over', compact('turned_overs'));
    }

    public function add_turned_overs(Request $request, BloodTurnoverService $turnovers)
    {
        $validatedAttributes = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'blood_type' => 'required|string|max:255',
            'volume' => 'required|integer|min:1',
        ]);

        $turned = $turnovers->save($validatedAttributes);

        session()->flash('success', 'Turned over record created successfully.');

        $this->logActivity("Turned over {$turned->name} {$turned->blood_type}");

        return redirect()->back();
    }

    public function delete_turned_overs($id, BloodTurnoverService $turnovers)
    {
        $record = $turnovers->delete((int) $id);
        $this->logActivity("Deleted turned over {$record->name} {$record->blood_type}");

        return response()->json(['success' => true]);
    }

    public function update_blood_appointments(Request $request, $pId)
    {
        $client = RhuAppointment::findOrFail($pId);
        Gate::authorize('manage-appointment', $client);

        $validatedData = $request->validate([
            'status' => 'required|string|in:Pending,Approved,Completed,Rejected',
        ]);

        $client->update($validatedData);

        $this->logActivity("Updated blood appointment for {$client->name}");

        session()->flash('success', 'Appointment updated successfully.');

        return redirect()->back();
    }

    public function update_turned_overs(Request $request, $pId, BloodTurnoverService $turnovers)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'blood_type' => 'required|string|max:255',
            'volume' => 'required|integer|min:1',
        ]);

        $turned_over = $turnovers->save($validatedData, (int) $pId);

        $this->logActivity("Updated turned over record for {$turned_over->name}");

        session()->flash('success', 'Turned over record updated successfully.');

        return redirect()->back();
    }
}
