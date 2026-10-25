<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\RhuAppointment;
use App\Models\RhuClient;
use App\Models\RhuSupply;
use App\Models\RhuUser;
use App\Services\AppointmentRecordService;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * Handles vaccination department operations: appointments, patient records,
 * and dose tracking.
 */
class VaccinationController extends Controller
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
            'type' => 'required|in:vaccination',
            'sub_type' => 'required|string|max:255',
            'dose_number' => 'required|max:255',
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
            'dose_number' => $request->dose_number,
            'date' => $formattedDate,
            'client_id' => Auth::id(),
            'status' => 'Pending',
            'email' => auth()->user()->email,
            'contactNo' => auth()->user()->contactNo,
        ]);

        $user = Auth::user();
        $this->logActivity("{$user->f_name} {$user->l_name} Added new appointment to vaccination.");

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

    public function reports()
    {
        if (auth()->user()->department !== 'VACCINATION') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $patients = RhuClient::where('type', 'vaccination')->get();
        $blood_appointments = RhuAppointment::where('type', 'vaccination')->get();

        return view('vaccination.reports', compact('patients', 'blood_appointments'));
    }

    public function vaccination()
    {
        if (auth()->user()->department !== 'VACCINATION') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $pendingCount = RhuAppointment::where('status', 'Pending')->where('type', 'vaccination')->count();
        $approvedCount = RhuAppointment::where('status', 'Approved')->where('type', 'vaccination')->count();
        $totalCount = $pendingCount + $approvedCount;
        $completedCount = RhuClient::where('status', 'Completed')->where('type', 'vaccination')->count();

        return view('vaccination.homepage', [
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

    public function display_vax_appointments()
    {
        if (auth()->user()->department !== 'VACCINATION') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $vax_appointments = RhuAppointment::where('type', 'vaccination')
            ->whereIn('status', ['Pending', 'Approved'])
            ->get();

        return view('vaccination.display_appointments', compact('vax_appointments'));
    }

    public function update_vax_appointments(Request $request, $pId)
    {
        $client = RhuAppointment::findOrFail($pId);
        Gate::authorize('manage-appointment', $client);

        $validatedData = $request->validate([
            'status' => 'required|string|in:Pending,Approved,Completed,Rejected',
        ]);

        $client->update($validatedData);

        $this->logActivity("Updated vaccination appointment for {$client->name}");

        session()->flash('success', 'Appointment updated successfully.');

        return redirect()->back();
    }

    public function vax_staff_management()
    {
        if (auth()->user()->department !== 'VACCINATION') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $staffs = RhuUser::where('role', 'Admin')
            ->where('department', 'VACCINATION')
            ->get()
            ->map(function ($staff) {
                if ($staff->bday) {
                    $staff->bday = Carbon::parse($staff->bday)->format('Y-m-d');
                }

                return $staff;
            })
            ->sortBy('created_at');

        return view('vaccination.staff_account', compact('staffs'));
    }

    public function vax_patient_records()
    {
        if (auth()->user()->department !== 'VACCINATION') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $patients = RhuClient::where('type', 'vaccination')
            ->whereIn('status', ['Completed', 'Rejected'])
            ->get();

        $appointments = RhuAppointment::where('type', 'vaccination')
            ->where('status', 'Approved')
            ->get();

        return view('vaccination.lab_patient_records', compact('patients', 'appointments'));
    }

    public function add_vax_patient_records(Request $request, AppointmentRecordService $records)
    {
        $attributes = $request->validate([
            'appointment_id' => 'required|integer',
            'dose_number' => 'required|string|max:255',
            'status' => 'required|string|in:Completed,Rejected',
        ]);

        $client = $records->record((int) $attributes['appointment_id'], 'vaccination', $attributes);
        $this->logActivity("Added {$client->name} to vaccination patient records.");

        return redirect()->back()->with('success', 'Record added successfully!');
    }

    public function getSubType($name)
    {
        $appointment = RhuAppointment::where('name', $name)
            ->where('type', 'vaccination')
            ->first();

        if ($appointment) {
            return response()->json(['sub_type' => $appointment->sub_type]);
        }

        return response()->json(['error' => 'No data found'], 404);
    }
}
