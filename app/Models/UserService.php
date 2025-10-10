<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserService
 * 
 * @property int|null $id_user
 * @property int|null $id_service
 * 
 * @property Service|null $service
 * @property User|null $user
 *
 * @package App\Models
 */
class UserService extends Model
{
	protected $table = 'user_service';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'id_service' => 'int'
	];

	protected $fillable = [
		'id_user',
		'id_service'
	];

	public function service()
	{
		return $this->belongsTo(Service::class, 'id_service');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
