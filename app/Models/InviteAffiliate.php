<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InviteAffiliate
 * 
 * @property int $id
 * @property int $id_inviter
 * @property int $id_invited
 * @property int $id_invite_invoice
 * @property int|null $id_reward_invoice
 * @property Carbon $created_at
 * 
 * @property Invoice|null $invoice
 * @property User $user
 *
 * @package App\Models
 */
class InviteAffiliate extends Model
{
	protected $table = 'invite_affiliate';
	public $timestamps = false;

	protected $casts = [
		'id_inviter' => 'int',
		'id_invited' => 'int',
		'id_invite_invoice' => 'int',
		'id_reward_invoice' => 'int'
	];

	protected $fillable = [
		'id_inviter',
		'id_invited',
		'id_invite_invoice',
		'id_reward_invoice'
	];

	public function invoice()
	{
		return $this->belongsTo(Invoice::class, 'id_reward_invoice');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_inviter');
	}
}
