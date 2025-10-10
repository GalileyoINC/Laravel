<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class SettingsSafe
 * 
 * @property string $name
 * @property string $value
 *
 * @package App\Models
 */
class SettingsSafe extends Model
{
	use HasFactory;

	protected $table = 'settings_safe';
	protected $primaryKey = 'name';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'value'
	];
}
