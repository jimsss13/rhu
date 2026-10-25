<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a blood type inventory record for blood bank tracking.
 *
 * @property int $id
 * @property string $blood_type
 * @property int $total
 * @property int $current
 * @property int $turned_over
 */
class RhuBloodType extends Model
{
    protected $table = 'rhu_blood_type';

    protected $fillable = [
        'blood_type',
        'total',
        'current',
        'turned_over',
    ];

    protected $casts = [
        'total' => 'integer',
        'current' => 'integer',
        'turned_over' => 'integer',
    ];
}
