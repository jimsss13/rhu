<?php

namespace App\Http\Controllers;

use App\Models\RhuAnnouncement;
use App\Models\RhuAppointment;
use App\Models\RhuClient;
use App\Models\RhuUser;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Handles super admin operations: announcements, staff overview,
 * all-department patient records, and appointments.
 */
class SuperAdminController extends Controller
{
    use LogsActivity;

    protected $currentDepartment;

    protected $currentRole;

    public function __construct()
    {
        $this->currentDepartment = session('currentDepartment');
        $this->currentRole = session('currentRole');
    }

    public function super_admin()
    {
        if (auth()->user()->department !== 'SUPER ADMIN') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        return view('super_admin.homepage', [
            'totalConsultation' => RhuAppointment::where('type', 'consultation')->count(),
            'totalVaccination' => RhuAppointment::where('type', 'vaccination')->count(),
            'totalLaboratory' => RhuAppointment::where('type', 'laboratory')->count(),
            'totalBlood' => RhuAppointment::where('type', 'blood')->count(),
            'currentDepartment' => $this->currentDepartment,
        ]);
    }

    public function super_patient()
    {
        if (auth()->user()->department !== 'SUPER ADMIN') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $patients = RhuClient::all();

        return view('super_admin.super_patient', compact('patients'));
    }

    public function announcement()
    {
        if (auth()->user()->department !== 'SUPER ADMIN') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $announcements = RhuAnnouncement::orderBy('created_at', 'asc')->get()
            ->map(function ($announcement) {
                if ($announcement->date) {
                    $announcement->date = Carbon::parse($announcement->date)->format('Y-m-d');
                }

                return $announcement;
            });

        return view('super_admin.announcement', compact('announcements'));
    }

    public function announcement_store(Request $request)
    {
        $validatedAttributes = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'fullcontext' => ['required', 'string'],
            'date' => ['required', 'date'],
            'description' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filePath = $file->store('images', 'public');
            $validatedAttributes['image'] = $filePath;
        }

        $validatedAttributes['isShow'] = 'Yes';

        $announcement = RhuAnnouncement::create($validatedAttributes);

        $this->logActivity("Added new announcement. Title: {$announcement->title}");

        return redirect()->back()->with('success', 'Announcement created successfully.');
    }

    public function deleteAnnouncement($id)
    {
        try {
            $announcement = RhuAnnouncement::findOrFail($id);
            $announcement->delete();

            $this->logActivity("Deleted announcement. Title: {$announcement->title}");

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateAnnouncement(Request $request, $pId)
    {
        $announcement = RhuAnnouncement::findOrFail($pId);

        $validatedAttributes = $request->validate([
            'editTitle' => ['required', 'string', 'max:255'],
            'editFullcontext' => ['required', 'string'],
            'editDate' => ['required', 'date'],
            'editDescription' => ['required', 'string'],
            'editImage' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'editIsShow' => ['required', 'in:Yes,No'],
        ]);

        $mappedAttributes = [
            'title' => $validatedAttributes['editTitle'],
            'fullcontext' => $validatedAttributes['editFullcontext'],
            'date' => $validatedAttributes['editDate'],
            'description' => $validatedAttributes['editDescription'],
            'image' => $announcement->image,
            'isShow' => $validatedAttributes['editIsShow'],
        ];

        if ($request->hasFile('editImage')) {
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }

            $file = $request->file('editImage');
            $filePath = $file->store('images', 'public');
            $mappedAttributes['image'] = $filePath;
        }

        $announcement->update($mappedAttributes);

        $this->logActivity("Updated announcement: {$announcement->title}");

        session()->flash('success', 'Announcement updated successfully.');

        return redirect()->back();
    }

    public function super_admin_staff_account()
    {
        if (auth()->user()->department !== 'SUPER ADMIN') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $staffs = RhuUser::where('role', 'Admin')
            ->get()
            ->map(function ($staff) {
                if ($staff->bday) {
                    $staff->bday = Carbon::parse($staff->bday)->format('Y-m-d');
                }

                return $staff;
            })
            ->sortBy('created_at');

        return view('super_admin.staff_account', compact('staffs'));
    }

    public function appointments()
    {
        if (auth()->user()->department !== 'SUPER ADMIN') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $appointments = RhuAppointment::all();

        return view('super_admin.appointments', compact('appointments'));
    }
}
