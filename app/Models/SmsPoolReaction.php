<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class SmsPoolReaction
 * 
 * @property int $id_sms_pool
 * @property int $id_user
 * @property int|null $id_reaction
 * @property Carbon $created_at
 * 
 * @property Reaction|null $reaction
 * @property SmsPool $sms_pool
 * @property User $user
 *
 * @package App\Models
 */
class SmsPoolReaction extends Model
{
	use HasFactory;

	protected $table = 'sms_pool_reaction';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_sms_pool' => 'int',
		'id_user' => 'int',
		'id_reaction' => 'int'
	];

	protected $fillable = [
		'id_reaction'
	];

	public function reaction()
	{
		return $this->belongsTo(Reaction::class, 'id_reaction');
	}

	public function sms_pool()
	{
		return $this->belongsTo(SmsPool::class, 'id_sms_pool');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
