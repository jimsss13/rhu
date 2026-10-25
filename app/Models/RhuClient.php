<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a patient record (completed/rejected appointment outcome).
 *
 * @property int $id
 * @property int|null $client_id
 * @property string $name
 * @property string $type
 * @property string $sub_type
 * @property string|null $doctor
 * @property string|null $reason
 * @property string|null $dose_number
 * @property string $date
 * @property string|null $result
 * @property int|null $volume
 * @property string|null $analysis
 * @property string $status
 */
class RhuClient extends Model
{
    use HasFactory;

    protected $table = 'rhu_client';

    protected $fillable = [
        'client_id',
        'name',
        'type',
        'sub_type',
        'doctor',
        'reason',
        'dose_number',
        'date',
        'result',
        'volume',
        'analysis',
        'status',
    ];

    protected $casts = [
        'date' => 'datetime',
        'volume' => 'integer',
    ];

    /**
     * The user/patient associated with this record.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(RhuUser::class, 'client_id');
    }
}
