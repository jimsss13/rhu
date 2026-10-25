<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents an activity log entry in the RHU system.
 *
 * Each log records a user action with timestamps and department context.
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $department
 * @property string $position
 * @property string $activity
 * @property string $date
 * @property string|null $time_in
 * @property string|null $time_out
 */
class Log extends Model
{
    protected $table = 'logs';

    protected $fillable = [
        'user_id',
        'name',
        'department',
        'position',
        'activity',
        'date',
        'time_in',
        'time_out',
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];

    /**
     * The user who performed this activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(RhuUser::class, 'user_id');
    }
}
