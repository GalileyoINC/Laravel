<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Subscription
 * 
 * @property int $id
 * @property int $id_subscription_category
 * @property string $name
 * @property string|null $title
 * @property int|null $position_no
 * @property string|null $description
 * @property string|null $rule
 * @property int|null $alert_type
 * @property int|null $is_active
 * @property int $is_hidden
 * @property float $percent
 * @property Carbon|null $sended_at
 * @property array|null $params
 * @property bool|null $is_custom
 * @property int|null $id_influencer
 * @property float $price
 * @property int $bonus_point
 * @property string|null $token
 * @property string|null $ipfs_id
 * @property bool $is_public
 * @property bool $is_fake
 * @property int|null $type
 * @property bool $show_reactions
 * @property bool $show_comments
 * 
 * @property User|null $user
 * @property SubscriptionCategory $subscription_category
 * @property Collection|InfluencerPage[] $influencer_pages
 * @property Collection|SmsShedule[] $sms_shedules
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Subscription extends Model
{
	protected $table = 'subscription';
	public $timestamps = false;

	protected $casts = [
		'id_subscription_category' => 'int',
		'position_no' => 'int',
		'alert_type' => 'int',
		'is_active' => 'int',
		'is_hidden' => 'int',
		'percent' => 'float',
		'sended_at' => 'datetime',
		'params' => 'json',
		'is_custom' => 'bool',
		'id_influencer' => 'int',
		'price' => 'float',
		'bonus_point' => 'int',
		'is_public' => 'bool',
		'is_fake' => 'bool',
		'type' => 'int',
		'show_reactions' => 'bool',
		'show_comments' => 'bool'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'id_subscription_category',
		'name',
		'title',
		'position_no',
		'description',
		'rule',
		'alert_type',
		'is_active',
		'is_hidden',
		'percent',
		'sended_at',
		'params',
		'is_custom',
		'id_influencer',
		'price',
		'bonus_point',
		'token',
		'ipfs_id',
		'is_public',
		'is_fake',
		'type',
		'show_reactions',
		'show_comments'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_influencer');
	}

	public function subscription_category()
	{
		return $this->belongsTo(SubscriptionCategory::class, 'id_subscription_category');
	}

	public function influencer_pages()
	{
		return $this->hasMany(InfluencerPage::class, 'id_subscription');
	}

	public function sms_shedules()
	{
		return $this->hasMany(SmsShedule::class, 'id_subscription');
	}

	public function users()
	{
		return $this->belongsToMany(User::class, 'user_subscription_address', 'id_subscription', 'id_user')
					->withPivot('id', 'zip');
	}
}
