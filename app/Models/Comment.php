<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Comment
 * 
 * @property int $id
 * @property int|null $id_sms_pool
 * @property int|null $id_user
 * @property string $message
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property int|null $id_parent
 * @property bool $is_deleted
 * 
 * @property Comment|null $comment
 * @property SmsPool|null $sms_pool
 * @property User|null $user
 * @property Collection|Comment[] $comments
 * @property Collection|UserPointHistory[] $user_point_histories
 *
 * @package App\Models
 */
class Comment extends Model
{
	use HasFactory;

	protected $table = 'comment';

	protected $casts = [
		'id_sms_pool' => 'int',
		'id_user' => 'int',
		'id_parent' => 'int',
		'is_deleted' => 'bool'
	];

	protected $fillable = [
		'id_sms_pool',
		'id_user',
		'message',
		'id_parent',
		'is_deleted'
	];

	public function comment()
	{
		return $this->belongsTo(Comment::class, 'id_parent');
	}

	public function sms_pool()
	{
		return $this->belongsTo(SmsPool::class, 'id_sms_pool');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}

	public function comments()
	{
		return $this->hasMany(Comment::class, 'id_parent');
	}

	public function user_point_histories()
	{
		return $this->hasMany(UserPointHistory::class, 'id_comment');
	}
}
