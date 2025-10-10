<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Setting
 * 
 * @property string $name
 * @property string $prod
 * @property string $dev
 *
 * @package App\Models
 */
class Setting extends Model
{
	use HasFactory;

	protected $table = 'settings';
	protected $primaryKey = 'name';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'prod',
		'dev'
	];
}
