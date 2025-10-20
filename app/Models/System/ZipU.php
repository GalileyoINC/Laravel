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
 * @property mixed $geopoint
 * @property string|null $city
 * @property string|null $state
 * @property string|null $timezone
 * @property string|null $daylight_savings_time_flag
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ZipU newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ZipU newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ZipU query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ZipU whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ZipU whereDaylightSavingsTimeFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ZipU whereGeopoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ZipU whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ZipU whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ZipU whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ZipU whereZip($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ZipUFactory>
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
