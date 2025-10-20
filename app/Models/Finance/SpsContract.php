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

/**
 * Class SpsContract
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_contract
 * @property int|null $id_plan
 * @property int $id_service
 * @property int $alert
 * @property int $max_phone_cnt
 * @property int $pay_interval
 * @property \Illuminate\Support\Carbon $begin_at
 * @property \Illuminate\Support\Carbon $ended_at
 * @property \Illuminate\Support\Carbon|null $terminated_at
 * @property int|null $is_secondary
 * @property array<array-key, mixed>|null $user_plan_data
 * @property-read Collection<int, ContractLine> $contract_lines
 * @property-read int|null $contract_lines_count
 * @property-read Service $service
 * @property-read \App\Models\User\User $user
 * @property-read \App\Models\User\UserPlan|null $user_plan
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract whereAlert($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract whereBeginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract whereIdContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract whereIdPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract whereIdService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract whereIsSecondary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract whereMaxPhoneCnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract wherePayInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract whereTerminatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsContract whereUserPlanData($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\SpsContractFactory>
 */
class SpsContract extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'sps_contract';

    protected $casts = [
        'id_user' => 'int',
        'id_contract' => 'int',
        'id_plan' => 'int',
        'id_service' => 'int',
        'alert' => 'int',
        'max_phone_cnt' => 'int',
        'pay_interval' => 'int',
        'begin_at' => 'datetime',
        'ended_at' => 'datetime',
        'terminated_at' => 'datetime',
        'is_secondary' => 'int',
        'user_plan_data' => 'json',
    ];

    protected $fillable = [
        'id_user',
        'id_contract',
        'id_plan',
        'id_service',
        'alert',
        'max_phone_cnt',
        'pay_interval',
        'begin_at',
        'ended_at',
        'terminated_at',
        'is_secondary',
        'user_plan_data',
    ];

    /**
     * @return BelongsTo<\App\Models\User\UserPlan, $this>
     */
    public function user_plan(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\UserPlan::class, 'id_plan');
    }

    /**
     * @return BelongsTo<Service, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'id_service');
    }

    /**
     * @return BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    /**
     * @return HasMany<ContractLine, $this>
     */
    public function contract_lines(): HasMany
    {
        return $this->hasMany(ContractLine::class, 'id_sps_contract');
    }
}
