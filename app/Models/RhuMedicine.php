<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tracks aggregated medicine inventory (total received, current stock, turned over).
 *
 * @property int $id
 * @property string $name
 * @property int $total
 * @property int $current
 * @property int|null $turned_over
 */
class RhuMedicine extends Model
{
    protected $table = 'rhu_medicine';

    protected $fillable = [
        'name',
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
