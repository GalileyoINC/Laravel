<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminMember
 * 
 * @property int $id
 * @property int|null $id_admin
 * @property int|null $id_member
 * @property int|null $id_plan
 * 
 * @property User|null $user
 * @property UserPlan|null $user_plan
 *
 * @package App\Models
 */
class AdminMember extends Model
{
	protected $table = 'admin_member';
	public $timestamps = false;

	protected $casts = [
		'id_admin' => 'int',
		'id_member' => 'int',
		'id_plan' => 'int'
	];

	protected $fillable = [
		'id_admin',
		'id_member',
		'id_plan'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_member');
	}

	public function user_plan()
	{
		return $this->belongsTo(UserPlan::class, 'id_plan');
	}
}
