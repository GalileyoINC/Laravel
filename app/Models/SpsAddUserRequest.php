<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class SpsAddUserRequest
 * 
 * @property int $id
 * @property int|null $id_user
 * @property string|null $token
 * @property array|null $post_data
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class SpsAddUserRequest extends Model
{
	use HasFactory;

	protected $table = 'sps_add_user_request';

	protected $casts = [
		'id_user' => 'int',
		'post_data' => 'json'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'id_user',
		'token',
		'post_data'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
