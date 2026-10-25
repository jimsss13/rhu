<?php

namespace App\Http\Controllers;

use App\Models\RhuUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

/**
 * Handles the forgot password flow: OTP generation, verification, and password reset.
 */
class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function showResetPasswordForm()
    {
        if (! Session::has('email')) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please try again.');
        }

        return view('auth.reset-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:rhu_user,email',
        ]);

        $user = RhuUser::where('email', $request->email)->first();

        if (! $user) {
            return back()->with('error', 'Email not found.');
        }

        $otp = random_int(100000, 999999);
        Session::put('otp', $otp);
        Session::put('email', $user->email);
        Session::put('otp_expires', now()->addMinutes(5));
        Session::put('otp_attempts', 0);

        Mail::raw("Your OTP code is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Password Reset OTP');
        });

        return redirect()->route('password.reset')->with('success', 'OTP sent to your email.');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (! Session::has(['otp', 'email', 'otp_expires']) || now()->greaterThanOrEqualTo(Session::get('otp_expires'))) {
            Session::forget(['otp', 'email', 'otp_expires', 'otp_attempts']);

            return back()->with('error', 'Invalid or expired OTP.');
        }

        $attempts = (int) Session::get('otp_attempts', 0) + 1;
        Session::put('otp_attempts', $attempts);

        if ($attempts > 5 || ! hash_equals((string) Session::get('otp'), (string) $request->otp)) {
            if ($attempts >= 5) {
                Session::forget(['otp', 'email', 'otp_expires', 'otp_attempts']);
            }

            return back()->with('error', 'Invalid or expired OTP.');
        }

        $user = RhuUser::where('email', Session::get('email'))->first();

        if (! $user) {
            return back()->with('error', 'User not found. Please try again.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        Session::forget(['otp', 'email', 'otp_expires', 'otp_attempts']);

        return redirect()->route('login')->with('success', 'Password reset successful. Please login.');
    }
}
