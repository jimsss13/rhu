<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

/**
 * Handles user authentication: login, logout, and session management.
 */
class SessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store()
    {
        $validatedAttributes = request()->validate([
            'username' => ['required'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($validatedAttributes)) {
            return back()->withErrors([
                'username' => 'Sorry, credentials do not match',
            ])->onlyInput('username');
        }

        $user = Auth::user();

        if (! in_array($user->status, ['Approved', 'Active'], true)) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('login')->with('status', 'Your account is not approved for access.');
        }

        request()->session()->regenerate();
        session(['currentDepartment' => $user->department, 'currentRole' => $user->role]);

        $existingLog = Log::where('user_id', $user->id)
            ->whereNull('time_out')
            ->first();

        if (! $existingLog) {
            Log::create([
                'user_id' => $user->id,
                'name' => $user->f_name.' '.$user->l_name,
                'department' => $user->department,
                'position' => $user->role,
                'time_in' => now(),
                'time_out' => null,
                'activity' => 'Login',
                'date' => now(),
            ]);
        }

        return match ($user->department) {
            'IT DEPARTMENT' => redirect('/it_dept'),
            'SUPER ADMIN' => redirect('/super_admin'),
            'INVENTORY' => redirect('/inventory'),
            'LABORATORY' => redirect('/laboratory'),
            'CONSULTATION' => redirect('/consultation'),
            'VACCINATION' => redirect('/vaccination'),
            'BLOOD' => redirect('/blood'),
            default => redirect('/'),
        };
    }

    public function destroy()
    {
        Log::where('user_id', Auth::user()->id)
            ->whereNull('time_out')
            ->update(['time_out' => now()]);

        $userId = Auth::user()->getAuthIdentifier();

        \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', $userId)
            ->delete();

        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
