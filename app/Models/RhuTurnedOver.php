<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a blood unit that has been turned over to a blood bank.
 *
 * @property int $id
 * @property string $date
 * @property string $name
 * @property string $blood_type
 * @property int $volume
 */
class RhuTurnedOver extends Model
{
    protected $table = 'rhu_turned_over';

    protected $fillable = [
        'date',
        'name',
        'blood_type',
        'volume',
    ];

    protected $casts = [
        'date' => 'date',
        'volume' => 'integer',
    ];
}
