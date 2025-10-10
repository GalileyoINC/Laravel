<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmergencyTipsRequest
 * 
 * @property int $id
 * @property string|null $first_name
 * @property string|null $email
 * @property Carbon $created_at
 *
 * @package App\Models
 */
class EmergencyTipsRequest extends Model
{
	protected $table = 'emergency_tips_request';
	public $timestamps = false;

	protected $fillable = [
		'first_name',
		'email'
	];
}
