<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a supply item (medicine, medical supply, equipment, or vaccine).
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string|null $batchNo
 * @property string|null $dosage_f
 * @property string|null $dosage_s
 * @property string|null $expiration_date
 * @property string|null $location_code
 * @property int $quantity
 * @property string|null $expiration
 * @property string|null $end_user
 */
class RhuSupply extends Model
{
    protected $table = 'rhu_supplies';

    protected $fillable = [
        'name',
        'type',
        'batchNo',
        'dosage_f',
        'dosage_s',
        'expiration_date',
        'location_code',
        'quantity',
        'expiration',
        'end_user',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'expiration_date' => 'date:Y-m-d',
    ];
}
