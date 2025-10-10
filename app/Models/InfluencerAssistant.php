<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InfluencerAssistant
 * 
 * @property int $id_influencer
 * @property int $id_assistant
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class InfluencerAssistant extends Model
{
	protected $table = 'influencer_assistant';
	public $incrementing = false;

	protected $casts = [
		'id_influencer' => 'int',
		'id_assistant' => 'int'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_influencer');
	}
}
