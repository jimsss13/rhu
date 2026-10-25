<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Represents a user in the RHU system (staff, admin, or client/patient).
 *
 * @property int $id
 * @property string $f_name
 * @property string|null $m_name
 * @property string $l_name
 * @property string|null $suffix
 * @property string|null $bday
 * @property string|null $gender
 * @property string|null $contactNo
 * @property string|null $street
 * @property string|null $brgy
 * @property string|null $zip_code
 * @property string|null $municipality
 * @property string|null $province
 * @property string|null $address
 * @property string|null $upload_id
 * @property string|null $username
 * @property string|null $email
 * @property string $password
 * @property string $department
 * @property string $status
 * @property string|null $profile_picture
 * @property string $role
 */
class RhuUser extends Authenticatable
{
    use HasFactory;

    protected $table = 'rhu_user';

    protected $fillable = [
        'f_name',
        'm_name',
        'l_name',
        'suffix',
        'bday',
        'gender',
        'contactNo',
        'street',
        'brgy',
        'zip_code',
        'municipality',
        'province',
        'address',
        'upload_id',
        'username',
        'email',
        'department',
        'status',
        'profile_picture',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'bday' => 'date:Y-m-d',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────

    public function appointments(): HasMany
    {
        return $this->hasMany(RhuAppointment::class, 'client_id');
    }

    public function records(): HasMany
    {
        return $this->hasMany(RhuClient::class, 'client_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'client_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(Log::class, 'user_id');
    }

    public function medicalHistory(): HasOne
    {
        return $this->hasOne(RhuMedicalHistory::class, 'client_id');
    }
}
