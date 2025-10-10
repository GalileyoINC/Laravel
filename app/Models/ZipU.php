<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
 *
 * @package App\Models
 */
class ZipU extends Model
{
	use HasFactory;

	protected $table = 'zip_us';
	public $timestamps = false;

	protected $casts = [
		'geopoint' => 'point'
	];

	protected $fillable = [
		'zip',
		'geopoint',
		'city',
		'state',
		'timezone',
		'daylight_savings_time_flag'
	];
}
