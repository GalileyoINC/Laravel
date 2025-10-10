<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LoginStatistic
 * 
 * @property int $id
 * @property int $id_user
 * @property int|null $id_device
 * @property int $type
 * @property string|null $ip
 * @property string|null $user_agent
 * @property array|null $data
 * @property Carbon $created_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class LoginStatistic extends Model
{
	use HasFactory;

	protected $table = 'login_statistic';
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'id_device' => 'int',
		'type' => 'int',
		'data' => 'json'
	];

	protected $fillable = [
		'id_user',
		'id_device',
		'type',
		'ip',
		'user_agent',
		'data'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
