<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class UserPlan
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_service
 * @property int|null $id_invoice_line
 * @property bool $is_primary
 * @property int|null $alert
 * @property int|null $max_phone_cnt
 * @property int|null $pay_interval
 * @property float|null $price_before_prorate
 * @property float|null $price_after_prorate
 * @property array<array-key, mixed>|null $settings
 * @property \Illuminate\Support\Carbon $begin_at
 * @property \Illuminate\Support\Carbon|null $end_at
 * @property int $devices
 * @property bool $is_new_custom
 * @property bool $is_not_receive_message
 * @property-read Collection<int, AdminMember> $admin_members
 * @property-read int|null $admin_members_count
 * @property-read \App\Models\Finance\InvoiceLine|null $invoice_line
 * @property-read Collection<int, MemberTemplate> $member_templates
 * @property-read int|null $member_templates_count
 * @property-read \App\Models\Finance\Service|null $service
 * @property-read Collection<int, \App\Models\Finance\SpsContract> $sps_contracts
 * @property-read int|null $sps_contracts_count
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan whereAlert($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan whereBeginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan whereDevices($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan whereIdInvoiceLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan whereIdService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan whereIsNewCustom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan whereIsNotReceiveMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan whereMaxPhoneCnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan wherePayInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan wherePriceAfterProrate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan wherePriceBeforeProrate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPlan whereSettings($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\UserUserPlanFactory>
 */
class UserPlan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'user_plan';

    protected $casts = [
        'id_user' => 'int',
        'id_service' => 'int',
        'id_invoice_line' => 'int',
        'is_primary' => 'bool',
        'alert' => 'int',
        'max_phone_cnt' => 'int',
        'pay_interval' => 'int',
        'price_before_prorate' => 'float',
        'price_after_prorate' => 'float',
        'settings' => 'json',
        'begin_at' => 'datetime',
        'end_at' => 'datetime',
        'devices' => 'int',
        'is_new_custom' => 'bool',
        'is_not_receive_message' => 'bool',
    ];

    protected $fillable = [
        'id_user',
        'id_service',
        'id_invoice_line',
        'is_primary',
        'alert',
        'max_phone_cnt',
        'pay_interval',
        'price_before_prorate',
        'price_after_prorate',
        'settings',
        'begin_at',
        'end_at',
        'devices',
        'is_new_custom',
        'is_not_receive_message',
    ];

    /**
     * Helper for controllers/views to render pay interval options.
     */
    public static function getPayIntervals(): array
    {
        return [
            1 => 'Monthly',
            12 => 'Annual',
        ];
    }

    /**
     * Get the expiration date attribute (alias for end_at)
     */
    public function getExpDateAttribute(): ?\Illuminate\Support\Carbon
    {
        return $this->end_at;
    }

    /**
     * @return BelongsTo<\App\Models\Finance\InvoiceLine, $this>
     */
    public function invoice_line(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Finance\InvoiceLine::class, 'id_invoice_line');
    }

    /**
     * @return BelongsTo<\App\Models\Finance\Service, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Finance\Service::class, 'id_service');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * @return HasMany<AdminMember, $this>
     */
    public function admin_members(): HasMany
    {
        return $this->hasMany(AdminMember::class, 'id_plan');
    }

    /**
     * @return HasMany<MemberTemplate, $this>
     */
    public function member_templates(): HasMany
    {
        return $this->hasMany(MemberTemplate::class, 'id_plan');
    }

    /**
     * @return HasMany<\App\Models\Finance\SpsContract, $this>
     */
    public function sps_contracts(): HasMany
    {
        return $this->hasMany(\App\Models\Finance\SpsContract::class, 'id_plan');
    }
}
