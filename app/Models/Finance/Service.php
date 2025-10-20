<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Service
 *
 * @property int $id
 * @property int $type
 * @property string|null $name
 * @property string|null $description
 * @property float|null $price
 * @property int $bonus_point
 * @property array<array-key, mixed>|null $settings
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool|null $is_active
 * @property array<array-key, mixed>|null $compensation
 * @property float|null $fee
 * @property float|null $fee_annual
 * @property float|null $termination_fee
 * @property int|null $termination_period
 * @property float|null $special_price
 * @property bool $is_special_price
 * @property float|null $special_fee
 * @property bool $is_special_fee
 * @property float|null $special_fee_annual
 * @property bool $is_special_fee_annual
 * @property \Illuminate\Support\Carbon|null $special_datetime
 * @property-read Collection<int, BundleItem> $bundle_items
 * @property-read int|null $bundle_items_count
 * @property-read Collection<int, ContractLine> $contract_lines
 * @property-read int|null $contract_lines_count
 * @property-read Collection<int, \App\Models\Device\DevicePlan> $device_plans
 * @property-read int|null $device_plans_count
 * @property-read Collection<int, Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read Collection<int, \App\Models\Content\ProductPhoto> $product_photos
 * @property-read int|null $product_photos_count
 * @property-read Collection<int, SpsContract> $sps_contracts
 * @property-read int|null $sps_contracts_count
 * @property-read Collection<int, \App\Models\User\UserPlanShedule> $user_plan_shedules
 * @property-read int|null $user_plan_shedules_count
 * @property-read Collection<int, \App\Models\User\UserPlan> $user_plans
 * @property-read int|null $user_plans_count
 * @property-read Collection<int, \App\Models\User\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\ServiceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereBonusPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCompensation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereFeeAnnual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIsSpecialFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIsSpecialFeeAnnual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIsSpecialPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereSpecialDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereSpecialFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereSpecialFeeAnnual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereSpecialPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereTerminationFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereTerminationPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ServiceFactory>
 */
class Service extends Model
{
    use HasFactory;

    public const TYPE_SUBSCRIBE = 1;

    public const TYPE_DEVICE_PLAN = 2;

    public const TYPE_ALERT = 3;

    // Optional IDs for custom plans (used in some legacy blades)
    public const ID_CUSTOM_WITH_SATELLITE = 999001;

    public const ID_CUSTOM_WITHOUT_SATELLITE = 999002;

    protected $table = 'service';

    protected $casts = [
        'type' => 'int',
        'price' => 'float',
        'bonus_point' => 'int',
        'settings' => 'json',
        'is_active' => 'bool',
        'compensation' => 'json',
        'fee' => 'float',
        'fee_annual' => 'float',
        'termination_fee' => 'float',
        'termination_period' => 'int',
        'special_price' => 'float',
        'is_special_price' => 'bool',
        'special_fee' => 'float',
        'is_special_fee' => 'bool',
        'special_fee_annual' => 'float',
        'is_special_fee_annual' => 'bool',
        'special_datetime' => 'datetime',
    ];

    protected $fillable = [
        'type',
        'name',
        'description',
        'price',
        'bonus_point',
        'settings',
        'is_active',
        'compensation',
        'fee',
        'fee_annual',
        'termination_fee',
        'termination_period',
        'special_price',
        'is_special_price',
        'special_fee',
        'is_special_fee',
        'special_fee_annual',
        'is_special_fee_annual',
        'special_datetime',
    ];

    // ===== Helpers expected by controllers =====
    public static function loadCustomParams(): array
    {
        // Placeholder: return empty params until customized
        return [];
    }

    public function bundle_items(): HasMany
    {
        return $this->hasMany(BundleItem::class, 'id_item');
    }

    public function contract_lines(): HasMany
    {
        return $this->hasMany(ContractLine::class, 'id_service');
    }

    public function device_plans(): HasMany
    {
        return $this->hasMany(\App\Models\Device\DevicePlan::class, 'id_plan');
    }

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class, 'invoice_service', 'id_service', 'id_invoice');
    }

    public function product_photos(): HasMany
    {
        return $this->hasMany(\App\Models\Content\ProductPhoto::class, 'id_service');
    }

    public function sps_contracts(): HasMany
    {
        return $this->hasMany(SpsContract::class, 'id_service');
    }

    public function user_plans(): HasMany
    {
        return $this->hasMany(\App\Models\User\UserPlan::class, 'id_service');
    }

    public function user_plan_shedules(): HasMany
    {
        return $this->hasMany(\App\Models\User\UserPlanShedule::class, 'id_service');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User\User::class, 'user_service', 'id_service', 'id_user');
    }

    public function isCustom(): bool
    {
        // Placeholder: treat as non-custom by default
        return false;
    }

    public function isNewSubscription(): bool
    {
        // Placeholder: consider subscription-type services as "new subscriptions"
        return (int) ($this->type ?? 0) === self::TYPE_SUBSCRIBE;
    }

    public function showFeedCnt(): ?string
    {
        $settings = is_array($this->settings ?? null) ? $this->settings : [];
        $value = $settings['feed_cnt']
            ?? $settings['max_feed_cnt']
            ?? $settings['feeds']
            ?? null;

        if (is_array($value)) {
            return implode(' - ', array_filter($value, fn ($v) => $v !== null && $v !== ''));
        }

        return $value !== null ? (string) $value : null;
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ServiceFactory::new();
    }
}
