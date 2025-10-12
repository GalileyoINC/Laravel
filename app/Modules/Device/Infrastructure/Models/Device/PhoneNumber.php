<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Device;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PhoneNumber
 *
 * @property int $id
 * @property int $id_user
 * @property int|null $id_provider
 * @property int|null $type
 * @property bool|null $is_satellite
 * @property string $number
 * @property bool|null $is_active
 * @property bool $is_primary
 * @property bool $is_send
 * @property bool $is_emergency_only
 * @property bool $is_valid
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $twilio_type
 * @property array|null $twilio_type_raw
 * @property string|null $numverify_type
 * @property array|null $numverify_raw
 * @property array|null $bivy_status
 * @property Provider|null $provider
 * @property User $user
 * @property Collection|SmsPool[] $sms_pools
 */
class PhoneNumber extends Model
{
    use HasFactory;

    protected $table = 'phone_number';

    protected $casts = [
        'id_user' => 'int',
        'id_provider' => 'int',
        'type' => 'int',
        'is_satellite' => 'bool',
        'is_active' => 'bool',
        'is_primary' => 'bool',
        'is_send' => 'bool',
        'is_emergency_only' => 'bool',
        'is_valid' => 'bool',
        'twilio_type_raw' => 'json',
        'numverify_raw' => 'json',
        'bivy_status' => 'json',
    ];

    protected $fillable = [
        'id_user',
        'id_provider',
        'type',
        'is_satellite',
        'number',
        'is_active',
        'is_primary',
        'is_send',
        'is_emergency_only',
        'is_valid',
        'twilio_type',
        'twilio_type_raw',
        'numverify_type',
        'numverify_raw',
        'bivy_status',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'id_provider');
    }

    public function user()
    {
        return $this->belongsTo(App\Models\User\User::class, 'id_user');
    }

    public function sms_pools()
    {
        return $this->belongsToMany(App\Models\Communication\SmsPool::class, 'sms_pool_phone_number', 'id_phone_number', 'id_sms_pool')
            ->withPivot('id', 'id_user', 'number', 'is_satellite', 'status', 'sid', 'error', 'id_provider', 'type');
    }
}
