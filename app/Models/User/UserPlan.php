<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @property array|null $settings
 * @property Carbon $begin_at
 * @property Carbon|null $end_at
 * @property int $devices
 * @property bool $is_new_custom
 * @property bool $is_not_receive_message
 * @property InvoiceLine|null $invoice_line
 * @property Service|null $service
 * @property User|null $user
 * @property Collection|AdminMember[] $admin_members
 * @property Collection|MemberTemplate[] $member_templates
 * @property Collection|SpsContract[] $sps_contracts
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

    public function invoice_line()
    {
        return $this->belongsTo(InvoiceLine::class, 'id_invoice_line');
    }

    public function service()
    {
        return $this->belongsTo(\App\Models\Finance\Service::class, 'id_service');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function admin_members()
    {
        return $this->hasMany(AdminMember::class, 'id_plan');
    }

    public function member_templates()
    {
        return $this->hasMany(MemberTemplate::class, 'id_plan');
    }

    public function sps_contracts()
    {
        return $this->hasMany(\App\Models\Finance\SpsContract::class, 'id_plan');
    }
}
