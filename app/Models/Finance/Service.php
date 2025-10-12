<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Service
 *
 * @property int $id
 * @property int $type
 * @property string|null $name
 * @property string|null $description
 * @property float|null $price
 * @property int $bonus_point
 * @property array|null $settings
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property bool|null $is_active
 * @property array|null $compensation
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
 * @property Carbon|null $special_datetime
 * @property Collection|BundleItem[] $bundle_items
 * @property Collection|ContractLine[] $contract_lines
 * @property Collection|DevicePlan[] $device_plans
 * @property Collection|Invoice[] $invoices
 * @property Collection|ProductPhoto[] $product_photos
 * @property Collection|SpsContract[] $sps_contracts
 * @property Collection|UserPlan[] $user_plans
 * @property Collection|UserPlanShedule[] $user_plan_shedules
 * @property Collection|User[] $users
 */
class Service extends Model
{
    use HasFactory;

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

    public function bundle_items()
    {
        return $this->hasMany(BundleItem::class, 'id_item');
    }

    public function contract_lines()
    {
        return $this->hasMany(App\Models\Finance\ContractLine::class, 'id_service');
    }

    public function device_plans()
    {
        return $this->hasMany(DevicePlan::class, 'id_plan');
    }

    public function invoices()
    {
        return $this->belongsToMany(App\Models\Finance\Invoice::class, 'invoice_service', 'id_service', 'id_invoice');
    }

    public function product_photos()
    {
        return $this->hasMany(ProductPhoto::class, 'id_service');
    }

    public function sps_contracts()
    {
        return $this->hasMany(App\Models\Finance\SpsContract::class, 'id_service');
    }

    public function user_plans()
    {
        return $this->hasMany(App\Models\User\UserPlan::class, 'id_service');
    }

    public function user_plan_shedules()
    {
        return $this->hasMany(App\Models\User\UserPlanShedule::class, 'id_service');
    }

    public function users()
    {
        return $this->belongsToMany(App\Models\User\User::class, 'user_service', 'id_service', 'id_user');
    }
}
