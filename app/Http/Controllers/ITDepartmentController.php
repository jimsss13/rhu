<?php

namespace App\Http\Controllers;

use App\Mail\AccountApproved;
use App\Models\Log;
use App\Models\RhuUser;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Mail;

/**
 * Handles IT Department operations: user/staff account management,
 * registration verification, client approval, and activity logs.
 */
class ITDepartmentController extends Controller
{
    use LogsActivity;

    protected $currentDepartment;

    protected $currentRole;

    public function __construct()
    {
        $this->currentDepartment = session('currentDepartment');
        $this->currentRole = session('currentRole');
    }

    public function it_dept()
    {
        if (auth()->user()->department !== 'IT DEPARTMENT') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $pendingCount = RhuUser::where('status', 'Pending')->count();
        $approvedCount = RhuUser::where('status', 'Approved')->count();
        $adminCount = RhuUser::where('role', 'admin')->count();
        $totalCount = $adminCount + $approvedCount;

        return view('it_dept.homepage', [
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'totalCount' => $totalCount,
            'adminCount' => $adminCount,
            'currentDepartment' => $this->currentDepartment,
        ]);
    }

    public function logs()
    {
        if (auth()->user()->department !== 'IT DEPARTMENT') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $logs = Log::orderBy('created_at', 'desc')->get();

        return view('it_dept.logs', ['logs' => $logs]);
    }

    public function user_account()
    {
        if (auth()->user()->department !== 'IT DEPARTMENT') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $clients = RhuUser::where('role', 'Client')
            ->where('status', 'Approved')
            ->get()
            ->map(function ($client) {
                if ($client->bday) {
                    $client->bday = Carbon::parse($client->bday)->format('Y-m-d');
                }

                return $client;
            })
            ->sortBy('created_at');

        return view('it_dept.user_account', compact('clients'));
    }

    public function staff_store(Request $request)
    {
        $validatedAttributes = $request->validate([
            'f_name' => ['required'],
            'l_name' => ['required'],
            'm_name' => ['required'],
            'username' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('rhu_user'),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('rhu_user'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'department' => ['required', 'string'],
            'status' => ['required', Rule::in(['Active', 'Approved', 'Pending', 'Rejected'])],
        ], [
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        Gate::authorize('assign-department', $validatedAttributes['department']);

        $validatedAttributes['role'] = 'Admin';
        $validatedAttributes['password'] = Hash::make($request->input('password'));

        $newUser = RhuUser::create($validatedAttributes);

        $this->logActivity("Added new admin: {$newUser->f_name} {$newUser->l_name}, Department: {$newUser->department}.");

        session()->flash('success', 'Admin created successfully.');

        return redirect()->back();
    }

    public function staff_account()
    {
        if (auth()->user()->department !== 'IT DEPARTMENT') {
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

        return view('it_dept.staff_account', compact('staffs'));
    }

    public function approveClient(Request $request, $id)
    {
        try {
            $client = RhuUser::findOrFail($id);
            $client->status = 'Approved';
            $client->save();

            $this->logActivity("Approved patient account: {$client->f_name} {$client->l_name}");

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function email(Request $request, $id)
    {
        $client = RhuUser::findOrFail($id);

        try {
            $account_mail_data_approved = [
                'title' => 'RHU Lucban',
                'client' => "{$client->f_name} {$client->l_name}",
                'body' => 'Congratulations! We are pleased to inform you that your account registration request has been approved. You can now log in and access all the features available on eHealth Lucban.',
            ];

            Mail::to($client->email)->send(new AccountApproved($account_mail_data_approved));

            return redirect()->back();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Approval email failed: '.$e->getMessage());

            return redirect()->back()->with('error', 'Failed to send approval email.');
        }
    }

    public function registration_verification()
    {
        if (auth()->user()->department !== 'IT DEPARTMENT') {
            return redirect('/')->with('error', 'Access Denied! You do not have the required permissions.');
        }

        $clients = RhuUser::where('role', 'Client')
            ->whereNot('status', 'Approved')
            ->get()
            ->map(function ($client) {
                $client->bday = Carbon::parse($client->bday)->format('Y-m-d');

                return $client;
            })
            ->sortBy('created_at');

        return view('it_dept.registration_verification', compact('clients'));
    }

    public function client_account_update(Request $request, $pId)
    {
        $client = RhuUser::findOrFail($pId);
        Gate::authorize('manage-user', $client);
        $user = Auth::user();

        if ($user->id == $client->id) {
            $request->merge([
                'status' => $client->status,
                'username' => $client->username,
                'department' => $client->department,
            ]);
        }

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
            'status' => ['sometimes', 'required', Rule::in(['Active', 'Approved', 'Pending', 'Rejected'])],
            'username' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('rhu_user')->ignore($client->id),
            ],
            'department' => 'sometimes|required|string|max:255',
        ]);

        if (isset($validatedData['department']) && $validatedData['department'] !== $client->department) {
            Gate::authorize('assign-department', $validatedData['department']);
        }

        $changes = [];
        foreach ($validatedData as $key => $value) {
            if ($value != $client->$key) {
                $changes[] = ucfirst(str_replace('_', ' ', $key)).' updated';
            }
        }

        $client->update($validatedData);

        $activityDescription = count($changes) > 0
            ? 'Updated staff account: '.implode(', ', $changes)
            : 'Updated staff account (no changes)';

        $this->logActivity($activityDescription);

        session()->flash('success', 'Updated successfully.');

        return redirect()->back();
    }

    public function deleteClient($id)
    {
        $client = RhuUser::findOrFail($id);
        Gate::authorize('manage-user', $client);

        try {
            $client->delete();

            session()->flash('success', 'User Deleted Successfully.');

            $this->logActivity("Deleted user: {$client->f_name} {$client->l_name}, Department: {$client->department}.");

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
