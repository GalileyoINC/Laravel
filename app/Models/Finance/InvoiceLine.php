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
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class InvoiceLine
 *
 * @property int $id
 * @property int|null $id_invoice
 * @property int $type
 * @property float|null $total
 * @property array|null $settings
 * @property int|null $id_service
 * @property int|null $pay_interval
 * @property int|null $quantity
 * @property int|null $id_contract_line
 * @property Carbon|null $begin_at
 * @property Carbon|null $end_at
 * @property int|null $id_bundle
 * @property Bundle|null $bundle
 * @property ContractLine|null $contract_line
 * @property Invoice|null $invoice
 * @property Collection|ContractLinePaid[] $contract_line_paids
 * @property Collection|UserPlan[] $user_plans
 */
class InvoiceLine extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'invoice_line';

    protected $casts = [
        'id_invoice' => 'int',
        'type' => 'int',
        'total' => 'float',
        'settings' => 'json',
        'id_service' => 'int',
        'pay_interval' => 'int',
        'quantity' => 'int',
        'id_contract_line' => 'int',
        'begin_at' => 'datetime',
        'end_at' => 'datetime',
        'id_bundle' => 'int',
    ];

    protected $fillable = [
        'id_invoice',
        'type',
        'total',
        'settings',
        'id_service',
        'pay_interval',
        'quantity',
        'id_contract_line',
        'begin_at',
        'end_at',
        'id_bundle',
    ];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'id_bundle');
    }

    public function contract_line()
    {
        return $this->belongsTo(ContractLine::class, 'id_contract_line');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'id_invoice');
    }

    public function contract_line_paids()
    {
        return $this->hasMany(ContractLinePaid::class, 'id_invoice_line');
    }

    public function user_plans()
    {
        return $this->hasMany(\App\Models\User\UserPlan::class, 'id_invoice_line');
    }
}
