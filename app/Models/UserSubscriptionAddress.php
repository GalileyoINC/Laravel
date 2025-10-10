<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class UserSubscriptionAddress
 * 
 * @property int $id
 * @property int $id_user
 * @property int $id_subscription
 * @property string|null $zip
 * 
 * @property Subscription $subscription
 * @property User $user
 *
 * @package App\Models
 */
class UserSubscriptionAddress extends Model
{
	use HasFactory;

	protected $table = 'user_subscription_address';
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'id_subscription' => 'int'
	];

	protected $fillable = [
		'id_user',
		'id_subscription',
		'zip'
	];

	public function subscription()
	{
		return $this->belongsTo(Subscription::class, 'id_subscription');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
