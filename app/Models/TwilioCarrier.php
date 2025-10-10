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
 * Class TwilioCarrier
 * 
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * 
 * @property Collection|Provider[] $providers
 *
 * @package App\Models
 */
class TwilioCarrier extends Model
{
	use HasFactory;

	protected $table = 'twilio_carrier';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function providers()
	{
		return $this->belongsToMany(Provider::class, 'provider_twilio_carrier', 'id_twilio_carrier', 'id_provider');
	}
}
