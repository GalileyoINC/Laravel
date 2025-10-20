<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Database\Factories\ProviderFactory as RootProviderFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @method static \Database\Factories\ProviderFactory factory($count = null, $state = [])
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
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ProviderFactory>
 */
class Provider extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
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

    /**
     * @return HasMany<\App\Models\Device\PhoneNumber, $this>
     */
    public function phone_numbers(): HasMany
    {
        return $this->hasMany(\App\Models\Device\PhoneNumber::class, 'id_provider');
    }

    /**
     * @return BelongsToMany<\App\Models\System\TwilioCarrier, $this>
     */
    public function twilio_carriers(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\System\TwilioCarrier::class, 'provider_twilio_carrier', 'id_provider', 'id_twilio_carrier');
    }

    /**
     * @return HasMany<\App\Models\Communication\SmsPoolPhoneNumber, $this>
     */
    public function sms_pool_phone_numbers(): HasMany
    {
        return $this->hasMany(\App\Models\Communication\SmsPoolPhoneNumber::class, 'id_provider');
    }

    /**
     * Create a new factory instance for the model.
     */
    /**
     * @return FinanceProviderFactory
     */
    protected static function newFactory(): RootProviderFactory
    {
        return RootProviderFactory::new();
    }
}
