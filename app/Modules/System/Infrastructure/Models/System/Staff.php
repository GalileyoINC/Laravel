<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Staff
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property int $role
 * @property int $status
 * @property int|null $is_superlogin
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property Collection|SmsPool[] $sms_pools
 * @property Collection|SmsShedule[] $sms_shedules
 */
class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $casts = [
        'role' => 'int',
        'status' => 'int',
        'is_superlogin' => 'int',
    ];

    protected $hidden = [
        'password_reset_token',
    ];

    protected $fillable = [
        'username',
        'email',
        'auth_key',
        'password_hash',
        'password_reset_token',
        'role',
        'status',
        'is_superlogin',
    ];

    public function sms_pools()
    {
        return $this->hasMany(App\Models\Communication\SmsPool::class, 'id_staff');
    }

    public function sms_shedules()
    {
        return $this->hasMany(App\Models\Communication\SmsShedule::class, 'id_staff');
    }
}
