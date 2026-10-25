<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! in_array($request->user()?->status, ['Approved', 'Active'], true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('status', 'Your account is not approved for access.');
        }

        return $next($request);
    }
}
