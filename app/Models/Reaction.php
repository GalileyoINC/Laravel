<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Reaction
 * 
 * @property int $id
 * @property string $emoji
 * 
 * @property Collection|SmsPool[] $sms_pools
 *
 * @package App\Models
 */
class Reaction extends Model
{
	protected $table = 'reaction';
	public $timestamps = false;

	protected $fillable = [
		'emoji'
	];

	public function sms_pools()
	{
		return $this->belongsToMany(SmsPool::class, 'sms_pool_reaction', 'id_reaction', 'id_sms_pool')
					->withPivot('id_user');
	}
}
