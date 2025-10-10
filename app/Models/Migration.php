<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Migration
 * 
 * @property string $version
 * @property int|null $apply_time
 *
 * @package App\Models
 */
class Migration extends Model
{
	protected $table = 'migration';
	protected $primaryKey = 'version';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'apply_time' => 'int'
	];

	protected $fillable = [
		'apply_time'
	];
}
