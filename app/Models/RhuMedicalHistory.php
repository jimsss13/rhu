<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a patient's medical history screening (used for blood donation eligibility).
 *
 * @property int $id
 * @property int $client_id
 * @property string $well_health
 * @property string $antibiotics
 * @property string|null $infection_medication
 * @property string $medication_deferral
 * @property string $aspirin
 * @property string|null $vaccinations
 * @property string $pregnant
 * @property string $donated_recently
 * @property string|null $apheresis
 * @property string $blood_transfusion
 * @property string $transplant
 * @property string $graft
 * @property string $contact_blood
 * @property string|null $needlestick_injury
 * @property string $sexual_contact_hiv
 * @property string $prostitute_contact
 * @property string|null $drug_use_contact
 * @property string $hemophilia_contact
 * @property string $male_contact_with_male
 * @property string|null $saliva_contact_hepatitis
 * @property string $contact_blood_hepatitis
 * @property string $sexual_contact_hepatitis
 * @property string $tattoo
 * @property string $piercing
 * @property string|null $acupuncture
 * @property string $syphilis_gonorrhea
 * @property string $juvenile_detention
 * @property string|null $hiv_aids_positive
 * @property string $used_needles
 * @property string $clotting_factor
 * @property string|null $hepatitis
 * @property string $malaria
 * @property string $chagas
 * @property string $babesiosis
 */
class RhuMedicalHistory extends Model
{
    protected $table = 'rhu_medical_history';

    protected $fillable = [
        'client_id',
        'well_health',
        'antibiotics',
        'infection_medication',
        'medication_deferral',
        'aspirin',
        'vaccinations',
        'pregnant',
        'donated_recently',
        'apheresis',
        'blood_transfusion',
        'transplant',
        'graft',
        'contact_blood',
        'needlestick_injury',
        'sexual_contact_hiv',
        'prostitute_contact',
        'drug_use_contact',
        'hemophilia_contact',
        'male_contact_with_male',
        'saliva_contact_hepatitis',
        'contact_blood_hepatitis',
        'sexual_contact_hepatitis',
        'tattoo',
        'piercing',
        'acupuncture',
        'syphilis_gonorrhea',
        'juvenile_detention',
        'hiv_aids_positive',
        'used_needles',
        'clotting_factor',
        'hepatitis',
        'malaria',
        'chagas',
        'babesiosis',
    ];

    /**
     * The patient this medical history belongs to.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(RhuUser::class, 'client_id');
    }
}
