<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a notification sent to a user in the RHU system.
 *
 * @property int $id
 * @property int $client_id
 * @property string $content
 * @property string $date
 * @property string $status
 */
class Notification extends Model
{
    protected $table = 'notification';

    protected $fillable = [
        'client_id',
        'content',
        'date',
        'status',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * The user this notification belongs to.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(RhuUser::class, 'client_id');
    }
}
