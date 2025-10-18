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
 * @property array|null $custom_data
 * @property Carbon|null $terminated_at
 * @property Carbon|null $begin_at
 * @property Carbon|null $end_at
 * @property bool $is_sps_line
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $title
 * @property Service|null $service
 * @property SpsContract|null $sps_contract
 * @property User|null $user
 * @property Collection|ContractLinePaid[] $contract_line_paids
 * @property Collection|InvoiceLine[] $invoice_lines
 * @property Collection|UserPlanShedule[] $user_plan_shedules
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

    public function service()
    {
        return $this->belongsTo(\App\Models\Finance\Service::class, 'id_service');
    }

    public function sps_contract()
    {
        return $this->belongsTo(\App\Models\Finance\SpsContract::class, 'id_sps_contract');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    public function contract_line_paids()
    {
        return $this->hasMany(ContractLinePaid::class, 'id_contract_line');
    }

    public function invoice_lines()
    {
        return $this->hasMany(InvoiceLine::class, 'id_contract_line');
    }

    public function user_plan_shedules()
    {
        return $this->hasMany(\App\Models\User\UserPlanShedule::class, 'id_contract_line');
    }

    // Added to support queries in ContractLineController@unpaid
    public function userPlan()
    {
        return $this->hasOne(\App\Models\User\UserPlan::class, 'id_user', 'id_user')
            ->whereColumn('user_plan.id_service', 'contract_line.id_service');
    }
}
