<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Database\Factories\SystemStaffFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Throwable;

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
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\Communication\SmsPool> $sms_pools
 * @property-read int|null $sms_pools_count
 * @property-read Collection<int, \App\Models\Communication\SmsShedule> $sms_shedules
 * @property-read int|null $sms_shedules_count
 *
 * @method static \Database\Factories\SystemStaffFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereAuthKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereIsSuperlogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff wherePasswordHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff wherePasswordResetToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereUsername($value)
 *
 * @mixin \Eloquent
 */
class Staff extends Model
{
    use HasFactory;

    public const ROLE_ADMIN = 1;

    public const STATUS_ACTIVE = 1;

    public const ID_SUPER = 1;

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

    public static function getForDropDown(): array
    {
        try {
            return self::query()
                ->orderBy('username')
                ->get(['id', 'username'])
                ->mapWithKeys(fn ($s) => [$s->id => (string) $s->username])
                ->toArray();
        } catch (Throwable) {
            return [];
        }
    }

    public function isSuper(): bool
    {
        return (int) ($this->is_superlogin ?? 0) === 1;
    }

    public function isAdmin(): bool
    {
        return (int) ($this->role ?? 0) === self::ROLE_ADMIN;
    }

    public function sms_pools()
    {
        return $this->hasMany(\App\Models\Communication\SmsPool::class, 'id_staff');
    }

    public function sms_shedules()
    {
        return $this->hasMany(\App\Models\Communication\SmsShedule::class, 'id_staff');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return SystemStaffFactory::new();
    }
}
