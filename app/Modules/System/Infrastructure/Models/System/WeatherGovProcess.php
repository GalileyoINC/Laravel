<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WeatherGovProcess
 *
 * @property int $id
 * @property string $api_id
 * @property Carbon $created_at
 */
class WeatherGovProcess extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'weather_gov_process';

    protected $fillable = [
        'api_id',
    ];
}
