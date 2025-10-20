<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class ContractLine
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_service
 * @property int|null $id_sps_contract
 * @property int|null $type
 * @property int|null $pay_interval
 * @property int|null $quantity
 * @property float|null $period_price
 * @property array<array-key, mixed>|null $custom_data
 * @property \Illuminate\Support\Carbon|null $terminated_at
 * @property \Illuminate\Support\Carbon|null $begin_at
 * @property \Illuminate\Support\Carbon|null $end_at
 * @property bool $is_sps_line
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $title
 * @property-read Collection<int, ContractLinePaid> $contract_line_paids
 * @property-read int|null $contract_line_paids_count
 * @property-read Collection<int, InvoiceLine> $invoice_lines
 * @property-read int|null $invoice_lines_count
 * @property-read Service|null $service
 * @property-read SpsContract|null $sps_contract
 * @property-read \App\Models\User\User|null $user
 * @property-read \App\Models\User\UserPlan|null $userPlan
 * @property-read Collection<int, \App\Models\User\UserPlanShedule> $user_plan_shedules
 * @property-read int|null $user_plan_shedules_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine whereBeginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine whereCustomData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine whereIdService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine whereIdSpsContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine whereIsSpsLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine wherePayInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine wherePeriodPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine whereTerminatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLine whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ContractLineFactory>
 */
class ContractLine extends Model
{
    use HasFactory;

    protected $table = 'contract_line';

    protected $casts = [
        'id_user' => 'int',
        'id_service' => 'int',
        'id_sps_contract' => 'int',
        'type' => 'int',
        'pay_interval' => 'int',
        'quantity' => 'int',
        'period_price' => 'float',
        'custom_data' => 'json',
        'terminated_at' => 'datetime',
        'begin_at' => 'datetime',
        'end_at' => 'datetime',
        'is_sps_line' => 'bool',
    ];

    protected $fillable = [
        'id_user',
        'id_service',
        'id_sps_contract',
        'type',
        'pay_interval',
        'quantity',
        'period_price',
        'custom_data',
        'terminated_at',
        'begin_at',
        'end_at',
        'is_sps_line',
        'title',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'id_service');
    }

    public function sps_contract(): BelongsTo
    {
        return $this->belongsTo(SpsContract::class, 'id_sps_contract');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    public function contract_line_paids(): HasMany
    {
        return $this->hasMany(ContractLinePaid::class, 'id_contract_line');
    }

    public function invoice_lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class, 'id_contract_line');
    }

    public function user_plan_shedules(): HasMany
    {
        return $this->hasMany(\App\Models\User\UserPlanShedule::class, 'id_contract_line');
    }

    // Added to support queries in ContractLineController@unpaid
    public function userPlan(): HasOne
    {
        return $this->hasOne(\App\Models\User\UserPlan::class, 'id_user', 'id_user')
            ->whereColumn('user_plan.id_service', 'contract_line.id_service');
    }
}
