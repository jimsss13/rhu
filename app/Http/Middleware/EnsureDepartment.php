<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class EnsureDepartment
{
    public function handle(Request $request, Closure $next, string $department): Response
    {
        Gate::authorize('staff');
        abort_unless($request->user()->department === $department, 403);

        return $next($request);
    }
}
