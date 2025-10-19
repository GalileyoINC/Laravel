<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Database\Factories\FinanceProviderFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Provider
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property bool|null $is_satellite
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $country
 * @property-read Collection<int, \App\Models\Device\PhoneNumber> $phone_numbers
 * @property-read int|null $phone_numbers_count
 * @property-read Collection<int, \App\Models\Communication\SmsPoolPhoneNumber> $sms_pool_phone_numbers
 * @property-read int|null $sms_pool_phone_numbers_count
 * @property-read Collection<int, \App\Models\System\TwilioCarrier> $twilio_carriers
 * @property-read int|null $twilio_carriers_count
 *
 * @method static \Database\Factories\FinanceProviderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereIsSatellite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Provider extends Model
{
    use HasFactory;

    protected $table = 'provider';

    protected $casts = [
        'is_satellite' => 'bool',
    ];

    protected $fillable = [
        'name',
        'email',
        'is_satellite',
        'country',
    ];

    public function phone_numbers()
    {
        return $this->hasMany(\App\Models\Device\PhoneNumber::class, 'id_provider');
    }

    public function twilio_carriers()
    {
        return $this->belongsToMany(\App\Models\System\TwilioCarrier::class, 'provider_twilio_carrier', 'id_provider', 'id_twilio_carrier');
    }

    public function sms_pool_phone_numbers()
    {
        return $this->hasMany(\App\Models\Communication\SmsPoolPhoneNumber::class, 'id_provider');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return FinanceProviderFactory::new();
    }
}
