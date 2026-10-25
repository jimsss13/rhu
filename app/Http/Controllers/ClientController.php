<?php

namespace App\Http\Controllers;

use App\Models\RhuAnnouncement;
use App\Models\RhuAppointment;
use App\Models\RhuSupply;
use App\Models\RhuTest;
use App\Models\RhuUser;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Handles client/patient-facing operations: homepage, profile editing,
 * appointment viewing, and account management.
 */
class ClientController extends Controller
{
    use LogsActivity;

    protected $currentDepartment;

    protected $currentRole;

    public function __construct()
    {
        $this->currentDepartment = session('currentDepartment');
        $this->currentRole = session('currentRole');
    }

    public function index()
    {
        $appointments = RhuAppointment::where('client_id', Auth::id())->get();
        $announcements = RhuAnnouncement::latest()
            ->where('isShow', 'Yes')
            ->get();
        $tests = RhuTest::where('status', 'Available')->get();
        $vaccines = RhuSupply::where('type', 'Vaccines')
            ->where('quantity', '>', 0)
            ->get();

        return view('rhu.index', compact('appointments', 'announcements', 'tests', 'vaccines'));
    }

    public function client()
    {
        return view('client.homepage');
    }

    public function it_dept()
    {
        if (auth()->user()->department !== 'IT DEPARTMENT') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        return view('it_dept.homepage', ['currentDepartment' => $this->currentDepartment]);
    }

    public function registration_verification()
    {
        if (auth()->user()->department !== 'IT DEPARTMENT') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $clients = RhuUser::where('role', 'Client')
            ->get()
            ->map(function ($client) {
                $client->bday = Carbon::parse($client->bday)->format('Y-m-d');

                return $client;
            });

        return view('it_dept.registration_verification', compact('clients'));
    }

    public function client_account_update(Request $request, $pId)
    {
        $client = RhuUser::findOrFail($pId);

        $validatedData = $request->validate([
            'f_name' => 'required|string|max:255',
            'm_name' => 'nullable|string|max:255',
            'l_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'bday' => 'nullable|date',
            'contactNo' => 'nullable|string|max:15',
            'gender' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'street' => 'nullable|string|max:255',
            'brgy' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'status' => 'nullable|string|max:255',
        ]);

        $client->update($validatedData);

        $this->logActivity("Updated {$client->f_name} {$client->l_name} account");

        return redirect()->back()->with('success', 'User Information Updated Successfully.');
    }

    public function client_account_destroy($pId)
    {
        $client = RhuUser::where('id', $pId)->first();

        if ($client) {
            $this->logActivity("Deleted {$client->f_name} {$client->l_name} account");

            $client->delete();

            return redirect()->back()->with('success', 'User deleted successfully.');
        }

        return redirect()->back()->with('error', 'User not found.');
    }

    public function edits()
    {
        $user = Auth::user();

        return view('edits', compact('user'));
    }

    public function edit()
    {
        return view('profiles.edit', ['user' => Auth::user()]);
    }

    public function edit_profile()
    {
        $user = Auth::user();

        return view('edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validatedAttributes = $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'f_name' => 'required|string|max:255',
            'm_name' => 'nullable|string|max:255',
            'l_name' => 'required|string|max:255',
            'bday' => 'required|date',
            'gender' => 'required|in:Male,Female,Prefer not to say',
            'contactNo' => 'required|string|max:15',
            'password' => 'nullable|min:8|confirmed',
            'username' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('rhu_user')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('rhu_user')->ignore($user->id),
            ],
        ], [
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filePath = $file->store('profile_pictures', 'public');
            $validatedAttributes['profile_picture'] = $filePath;
        }

        if ($request->filled('password')) {
            $validatedAttributes['password'] = Hash::make($request->input('password'));
        } else {
            unset($validatedAttributes['password']);
        }

        $user->update($validatedAttributes);

        session()->flash('success', 'Account Updated.');

        return redirect()->back();
    }

    public function update_edit_profile(Request $request)
    {
        $user = Auth::user();

        $validatedAttributes = $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'f_name' => 'required|string|max:255',
            'm_name' => 'nullable|string|max:255',
            'l_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'bday' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Prefer not to say',
            'contactNo' => 'nullable|string|max:15',
            'password' => 'nullable|min:8|confirmed',
            'username' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('rhu_user')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('rhu_user')->ignore($user->id),
            ],
        ], [
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filePath = $file->store('profile_pictures', 'public');
            $validatedAttributes['profile_picture'] = $filePath;
        }

        if ($request->filled('password')) {
            $validatedAttributes['password'] = Hash::make($request->input('password'));
        } else {
            unset($validatedAttributes['password']);
        }

        $user->update($validatedAttributes);

        session()->flash('success', 'Account Updated.');

        return redirect()->back();
    }
}
