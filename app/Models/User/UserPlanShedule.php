<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserPlanShedule
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_service
 * @property array|null $settings
 * @property Carbon|null $begin_at
 * @property Carbon|null $end_at
 * @property bool $is_complete
 * @property bool $is_new_custom
 * @property int|null $id_contract_line
 * @property ContractLine|null $contract_line
 * @property Service|null $service
 * @property User|null $user
 */
class UserPlanShedule extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'user_plan_shedule';

    protected $casts = [
        'id_user' => 'int',
        'id_service' => 'int',
        'settings' => 'json',
        'begin_at' => 'datetime',
        'end_at' => 'datetime',
        'is_complete' => 'bool',
        'is_new_custom' => 'bool',
        'id_contract_line' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_service',
        'settings',
        'begin_at',
        'end_at',
        'is_complete',
        'is_new_custom',
        'id_contract_line',
    ];

    public function contract_line()
    {
        return $this->belongsTo(\App\Models\Finance\ContractLine::class, 'id_contract_line');
    }

    public function service()
    {
        return $this->belongsTo(\App\Models\Finance\Service::class, 'id_service');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }
}
