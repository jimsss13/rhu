<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\RhuUser;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Handles new user (client/patient) registration.
 */
class RegisteredUserController extends Controller
{
    use LogsActivity;

    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validatedAttributes = $request->validate([
            'f_name' => ['nullable', 'string', 'max:255'],
            'm_name' => ['nullable', 'string', 'max:255'],
            'l_name' => ['nullable', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'bday' => ['required', 'date'],
            'gender' => ['required', 'in:male,female,prefer-not-say'],
            'contactNo' => ['required', 'string', 'regex:/^09\d{9}$/'],
            'street' => ['required', 'string', 'max:255'],
            'brgy' => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'max:10'],
            'municipality' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:254', 'unique:rhu_user,email'],
            'username' => ['required', 'string', 'max:255', 'unique:rhu_user,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'upload_id' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
        ], [
            'email.unique' => 'This email is already registered.',
            'username.unique' => 'This username is already taken.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        if ($request->hasFile('upload_id')) {
            $file = $request->file('upload_id');
            $filePath = $file->store('uploads', 'public');
            $validatedAttributes['upload_id'] = $filePath;
        }

        $validatedAttributes['department'] = 'Client';
        $validatedAttributes['gender'] = match ($validatedAttributes['gender']) {
            'male' => 'Male',
            'female' => 'Female',
            default => 'Prefer not to say',
        };
        $validatedAttributes['status'] = 'Pending';
        $validatedAttributes['role'] = 'Client';
        $validatedAttributes['password'] = Hash::make($validatedAttributes['password']);

        $user = RhuUser::create($validatedAttributes);

        // Don't auto-login a Pending user — redirect to login with a message
        session()->flash('success', 'Registration successful! Please wait for account verification.');

        Log::create([
            'user_id' => $user->id,
            'name' => $user->f_name.' '.$user->l_name,
            'department' => $user->department,
            'position' => $user->role,
            'time_in' => now(),
            'time_out' => now(),
            'activity' => "{$user->f_name} {$user->l_name} successfully registered.",
            'date' => now(),
        ]);

        return redirect('/login');
    }
}
