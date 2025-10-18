<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Carbon\Carbon;
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
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $country
 * @property Collection|PhoneNumber[] $phone_numbers
 * @property Collection|TwilioCarrier[] $twilio_carriers
 * @property Collection|SmsPoolPhoneNumber[] $sms_pool_phone_numbers
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
        return $this->belongsToMany(TwilioCarrier::class, 'provider_twilio_carrier', 'id_provider', 'id_twilio_carrier');
    }

    public function sms_pool_phone_numbers()
    {
        return $this->hasMany(SmsPoolApp\Models\Device\PhoneNumber::class, 'id_provider');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return FinanceProviderFactory::new();
    }
}
