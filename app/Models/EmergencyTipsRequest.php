<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
	use HasFactory;

	protected $table = 'emergency_tips_request';
	public $timestamps = false;

	protected $fillable = [
		'first_name',
		'email'
	];
}
