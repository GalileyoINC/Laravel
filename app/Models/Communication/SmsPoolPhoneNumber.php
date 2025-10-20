<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use App\Models\Finance\Provider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SmsPoolPhoneNumber
 *
 * @property int $id
 * @property int|null $id_sms_pool
 * @property int|null $id_user
 * @property int|null $id_phone_number
 * @property string|null $number
 * @property bool|null $is_satellite
 * @property int $status
 * @property string|null $sid
 * @property string|null $error
 * @property int|null $id_provider
 * @property int|null $type
 * @property-read \App\Models\Device\PhoneNumber|null $phoneNumber
 * @property-read \App\Models\Device\PhoneNumber|null $phone_number
 * @property-read Provider|null $provider
 * @property-read SmsPool|null $smsPool
 * @property-read SmsPool|null $sms_pool
 * @property-read \App\Models\User\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoneNumber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoneNumber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoneNumber query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoneNumber whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoneNumber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoneNumber whereIdPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoneNumber whereIdProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoneNumber whereIdSmsPool($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoneNumber whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoneNumber whereIsSatellite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoneNumber whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoneNumber whereSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoneNumber whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoneNumber whereType($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\CommunicationSmsPoolPhoneNumberFactory>
 */
class SmsPoolPhoneNumber extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'sms_pool_phone_number';

    protected $casts = [
        'id_sms_pool' => 'int',
        'id_user' => 'int',
        'id_phone_number' => 'int',
        'is_satellite' => 'bool',
        'status' => 'int',
        'id_provider' => 'int',
        'type' => 'int',
    ];

    protected $fillable = [
        'id_sms_pool',
        'id_user',
        'id_phone_number',
        'number',
        'is_satellite',
        'status',
        'sid',
        'error',
        'id_provider',
        'type',
    ];

    /**
     * @return BelongsTo<\App\Models\Device\PhoneNumber, $this>
     */
    public function phone_number(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Device\PhoneNumber::class, 'id_phone_number');
    }

    /** Alias to satisfy controllers using ->with(['phoneNumber']) */
    /**
     * @return BelongsTo<\App\Models\Device\PhoneNumber, $this>
     */
    public function phoneNumber(): BelongsTo
    {
        return $this->phone_number();
    }

    /**
     * @return BelongsTo<Provider, $this>
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'id_provider');
    }

    /**
     * @return BelongsTo<SmsPool, $this>
     */
    public function sms_pool(): BelongsTo
    {
        return $this->belongsTo(SmsPool::class, 'id_sms_pool');
    }

    /** Alias for consistency */
    /**
     * @return BelongsTo<SmsPool, $this>
     */
    public function smsPool(): BelongsTo
    {
        return $this->sms_pool();
    }

    /**
     * @return BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }
}
