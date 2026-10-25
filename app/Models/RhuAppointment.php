<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a patient appointment in the RHU system.
 *
 * @property int $id
 * @property int $client_id
 * @property string $name
 * @property string $type
 * @property string $sub_type
 * @property string|null $doctor
 * @property string|null $dose_number
 * @property string $date
 * @property string $status
 * @property string|null $contactNo
 * @property string|null $email
 * @property string|null $refer
 * @property string|null $reason
 * @property string|null $price
 * @property string|null $result
 */
class RhuAppointment extends Model
{
    use HasFactory;

    protected $table = 'rhu_appointment';

    protected $fillable = [
        'client_id',
        'name',
        'type',
        'sub_type',
        'doctor',
        'dose_number',
        'date',
        'status',
        'contactNo',
        'email',
        'refer',
        'reason',
        'price',
        'result',
    ];

    protected $casts = [
        'date' => 'datetime',
        'price' => 'decimal:2',
    ];

    /**
     * The patient/client who booked this appointment.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(RhuUser::class, 'client_id');
    }
}
