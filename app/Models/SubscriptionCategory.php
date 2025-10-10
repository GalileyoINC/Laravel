<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SubscriptionCategory
 * 
 * @property int $id
 * @property string $name
 * @property int|null $id_parent
 * @property int $position_no
 * 
 * @property Collection|Subscription[] $subscriptions
 *
 * @package App\Models
 */
class SubscriptionCategory extends Model
{
	protected $table = 'subscription_category';
	public $timestamps = false;

	protected $casts = [
		'id_parent' => 'int',
		'position_no' => 'int'
	];

	protected $fillable = [
		'name',
		'id_parent',
		'position_no'
	];

	public function subscriptions()
	{
		return $this->hasMany(Subscription::class, 'id_subscription_category');
	}
}
