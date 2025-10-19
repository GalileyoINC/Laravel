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
 * Class Invoice
 *
 * @property int $id
 * @property int $id_user
 * @property int|null $id_bp_subscribe
 * @property bool $paid_status
 * @property float $total
 * @property string|null $description
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property User $user
 * @property BpSubscription|null $bp_subscription
 * @property Collection|Address[] $addresses
 * @property Collection|ContractLinePaid[] $contract_line_paids
 * @property Collection|InviteAffiliate[] $invite_affiliates
 * @property Collection|InvoiceLine[] $invoice_lines
 * @property Collection|Promocode[] $promocodes
 * @property Collection|Service[] $services
 * @property Collection|MoneyTransaction[] $money_transactions
 */
class Invoice extends Model
{
    use HasFactory;

    public const PAY_STATUS_NONE = 0;

    public const PAY_STATUS_SUCCESS = 1;

    public const PAY_STATUS_BEGIN = 8;

    public const PAY_STATUS_ABORT = 9;

    protected $table = 'invoice';

    protected $casts = [
        'id_user' => 'int',
        'id_bp_subscribe' => 'int',
        'paid_status' => 'bool',
        'total' => 'float',
    ];

    protected $fillable = [
        'id_user',
        'id_bp_subscribe',
        'paid_status',
        'total',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    public function bp_subscription()
    {
        return $this->belongsTo(\App\Models\Subscription\BpApp\Models\Subscription\Subscription::class, 'id_bp_subscribe');
    }

    public function addresses()
    {
        return $this->hasMany(\App\Models\User\Address::class, 'id_invoice');
    }

    public function contract_line_paids()
    {
        return $this->hasMany(ContractLinePaid::class, 'id_invoice');
    }

    public function invite_affiliates()
    {
        return $this->hasMany(InviteApp\Models\User\Affiliate::class, 'id_reward_invoice');
    }

    public function invoice_lines()
    {
        return $this->hasMany(InvoiceLine::class, 'id_invoice');
    }

    public function promocodes()
    {
        return $this->belongsToMany(Promocode::class, 'invoice_promocode', 'id_invoice', 'id_promo')
            ->withPivot('id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'invoice_service', 'id_invoice', 'id_service');
    }

    public function money_transactions()
    {
        return $this->hasMany(MoneyTransaction::class, 'id_invoice');
    }
}
