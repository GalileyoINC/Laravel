<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @property PhoneNumber|null $phone_number
 * @property Provider|null $provider
 * @property SmsPool|null $sms_pool
 * @property User|null $user
 */
class SmsPoolPhoneNumber extends Model
{
    use HasFactory;

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

    public function phone_number()
    {
        return $this->belongsTo(\App\Models\Device\PhoneNumber::class, 'id_phone_number');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'id_provider');
    }

    public function sms_pool()
    {
        return $this->belongsTo(\App\Models\Communication\SmsPool::class, 'id_sms_pool');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }
}
