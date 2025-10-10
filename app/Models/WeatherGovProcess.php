<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class WeatherGovProcess
 * 
 * @property int $id
 * @property string $api_id
 * @property Carbon $created_at
 *
 * @package App\Models
 */
class WeatherGovProcess extends Model
{
	use HasFactory;

	protected $table = 'weather_gov_process';
	public $timestamps = false;

	protected $fillable = [
		'api_id'
	];
}
