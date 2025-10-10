<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MemberTemplate
 * 
 * @property int $id
 * @property int|null $id_admin
 * @property string|null $first_name
 * @property string|null $last_name
 * @property int|null $id_plan
 * @property string|null $email
 * @property string|null $member_key
 * @property Carbon|null $expired_at
 * 
 * @property UserPlan|null $user_plan
 * @property User|null $user
 *
 * @package App\Models
 */
class MemberTemplate extends Model
{
	protected $table = 'member_template';
	public $timestamps = false;

	protected $casts = [
		'id_admin' => 'int',
		'id_plan' => 'int',
		'expired_at' => 'datetime'
	];

	protected $fillable = [
		'id_admin',
		'first_name',
		'last_name',
		'id_plan',
		'email',
		'member_key',
		'expired_at'
	];

	public function user_plan()
	{
		return $this->belongsTo(UserPlan::class, 'id_plan');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_admin');
	}
}
