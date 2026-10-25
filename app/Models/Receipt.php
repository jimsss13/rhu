<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a payment receipt generated for a patient service.
 *
 * @property int $id
 * @property int|null $client_id
 * @property string $date
 * @property string $receipt_no
 * @property string $series
 * @property string $agency
 * @property string $payor
 * @property string $nature
 * @property string $account_code
 * @property float $amount
 * @property string $teller
 * @property string $collecting_officer
 */
class Receipt extends Model
{
    protected $table = 'receipt';

    protected $fillable = [
        'client_id',
        'date',
        'receipt_no',
        'series',
        'agency',
        'payor',
        'nature',
        'account_code',
        'amount',
        'teller',
        'collecting_officer',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];
}
