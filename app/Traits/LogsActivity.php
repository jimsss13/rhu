<?php

namespace App\Traits;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

/**
 * Provides a reusable method for logging user activity.
 *
 * Use this trait in any controller that needs to record activity logs.
 */
trait LogsActivity
{
    /**
     * Record an activity log entry for the currently authenticated user.
     */
    protected function logActivity(string $activity): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        Log::create([
            'user_id' => $user->id,
            'name' => "{$user->f_name} {$user->l_name}",
            'department' => $user->department,
            'position' => $user->role,
            'time_in' => now(),
            'time_out' => now(),
            'activity' => $activity,
            'date' => now(),
        ]);
    }
}
