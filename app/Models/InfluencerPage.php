<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InfluencerPage
 * 
 * @property int $id
 * @property int $id_subscription
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property string|null $image
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Subscription $subscription
 *
 * @package App\Models
 */
class InfluencerPage extends Model
{
	protected $table = 'influencer_page';

	protected $casts = [
		'id_subscription' => 'int'
	];

	protected $fillable = [
		'id_subscription',
		'title',
		'alias',
		'description',
		'image'
	];

	public function subscription()
	{
		return $this->belongsTo(Subscription::class, 'id_subscription');
	}
}
