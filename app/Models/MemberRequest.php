<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MemberRequest
 * 
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_user_from
 * @property int|null $type
 * @property string|null $text
 * @property array|null $params
 * @property int $is_active
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class MemberRequest extends Model
{
	use HasFactory;

	protected $table = 'member_request';
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'id_user_from' => 'int',
		'type' => 'int',
		'params' => 'json',
		'is_active' => 'int'
	];

	protected $fillable = [
		'id_user',
		'id_user_from',
		'type',
		'text',
		'params',
		'is_active'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user_from');
	}
}
