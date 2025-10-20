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
 * Class InvoiceLine
 *
 * @property int $id
 * @property int|null $id_invoice
 * @property int $type
 * @property float|null $total
 * @property array<array-key, mixed>|null $settings
 * @property int|null $id_service
 * @property int|null $pay_interval
 * @property int|null $quantity
 * @property int|null $id_contract_line
 * @property \Illuminate\Support\Carbon|null $begin_at
 * @property \Illuminate\Support\Carbon|null $end_at
 * @property int|null $id_bundle
 * @property-read Bundle|null $bundle
 * @property-read ContractLine|null $contract_line
 * @property-read Collection<int, ContractLinePaid> $contract_line_paids
 * @property-read int|null $contract_line_paids_count
 * @property-read Invoice|null $invoice
 * @property-read Collection<int, \App\Models\User\UserPlan> $user_plans
 * @property-read int|null $user_plans_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceLine query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceLine whereBeginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceLine whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceLine whereIdBundle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceLine whereIdContractLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceLine whereIdInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceLine whereIdService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceLine wherePayInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceLine whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceLine whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceLine whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceLine whereType($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\InvoiceLineFactory>
 */
class InvoiceLine extends Model
{
    use HasFactory; /** @phpstan-ignore-line */

    /** @phpstan-ignore-line */
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

    /**
     * @return BelongsTo<Bundle, $this>
     */
    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class, 'id_bundle');
    }

    /**
     * @return BelongsTo<ContractLine, $this>
     */
    public function contract_line(): BelongsTo
    {
        return $this->belongsTo(ContractLine::class, 'id_contract_line');
    }

    /**
     * @return BelongsTo<Invoice, $this>
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'id_invoice');
    }

    /**
     * @return HasMany<ContractLinePaid, $this>
     */
    public function contract_line_paids(): HasMany
    {
        return $this->hasMany(ContractLinePaid::class, 'id_invoice_line');
    }

    /**
     * @return HasMany<\App\Models\User\UserPlan, $this>
     */
    public function user_plans(): HasMany
    {
        return $this->hasMany(\App\Models\User\UserPlan::class, 'id_invoice_line');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\InvoiceLineFactory
    {
        return \Database\Factories\InvoiceLineFactory::new();
    }
}
