<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ZipU
 *
 * @property int $id
 * @property string $zip
 * @property point $geopoint
 * @property string|null $city
 * @property string|null $state
 * @property string|null $timezone
 * @property string|null $daylight_savings_time_flag
 */
class ZipU extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'zip_us';

    protected $casts = [
        'geopoint' => 'point',
    ];

    protected $fillable = [
        'zip',
        'geopoint',
        'city',
        'state',
        'timezone',
        'daylight_savings_time_flag',
    ];
}
