<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a laboratory test type offered by the RHU.
 *
 * @property int $id
 * @property string $name
 * @property string|null $price
 * @property string|null $status
 */
class RhuTest extends Model
{
    protected $table = 'rhu_test';

    protected $fillable = [
        'name',
        'price',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];
}
