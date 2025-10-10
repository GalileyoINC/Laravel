<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiLog
 * 
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property Carbon $created_at
 *
 * @package App\Models
 */
class ApiLog extends Model
{
	protected $table = 'api_log';
	public $timestamps = false;

	protected $fillable = [
		'key',
		'value'
	];
}
