<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SubscriptionWizard
 * 
 * @property int $id
 * @property int|null $id_user
 * @property array|null $settings
 * @property Carbon $created_at
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class SubscriptionWizard extends Model
{
	protected $table = 'subscription_wizard';
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'settings' => 'json'
	];

	protected $fillable = [
		'id_user',
		'settings'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
