<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class UserFriend
 * 
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_friend
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class UserFriend extends Model
{
	use HasFactory;

	protected $table = 'user_friend';
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'id_friend' => 'int'
	];

	protected $fillable = [
		'id_user',
		'id_friend'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
