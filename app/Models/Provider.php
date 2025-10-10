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
 * Class Provider
 * 
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property bool|null $is_satellite
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $country
 * 
 * @property Collection|PhoneNumber[] $phone_numbers
 * @property Collection|TwilioCarrier[] $twilio_carriers
 * @property Collection|SmsPoolPhoneNumber[] $sms_pool_phone_numbers
 *
 * @package App\Models
 */
class Provider extends Model
{
	use HasFactory;

	protected $table = 'provider';

	protected $casts = [
		'is_satellite' => 'bool'
	];

	protected $fillable = [
		'name',
		'email',
		'is_satellite',
		'country'
	];

	public function phone_numbers()
	{
		return $this->hasMany(PhoneNumber::class, 'id_provider');
	}

	public function twilio_carriers()
	{
		return $this->belongsToMany(TwilioCarrier::class, 'provider_twilio_carrier', 'id_provider', 'id_twilio_carrier');
	}

	public function sms_pool_phone_numbers()
	{
		return $this->hasMany(SmsPoolPhoneNumber::class, 'id_provider');
	}
}
