<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WeatherGovProcess
 *
 * @property int $id
 * @property string $api_id
 * @property string $created_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WeatherGovProcess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WeatherGovProcess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WeatherGovProcess query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WeatherGovProcess whereApiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WeatherGovProcess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WeatherGovProcess whereId($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\WeatherGovProcessFactory>
 */
class WeatherGovProcess extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'weather_gov_process';

    protected $fillable = [
        'api_id',
    ];
}
