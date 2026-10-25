<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents an announcement displayed on the RHU public-facing site.
 *
 * @property int $id
 * @property string|null $image
 * @property string $title
 * @property string $description
 * @property string $date
 * @property string $fullcontext
 * @property string $isShow
 */
class RhuAnnouncement extends Model
{
    protected $table = 'rhu_announcement';

    protected $fillable = [
        'image',
        'title',
        'description',
        'date',
        'fullcontext',
        'isShow',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];
}
