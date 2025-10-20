<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ContractLinePaid
 *
 * @property int $id
 * @property int|null $id_contract_line
 * @property int|null $id_invoice
 * @property int|null $id_invoice_line
 * @property float $total
 * @property \Illuminate\Support\Carbon|null $begin_at
 * @property \Illuminate\Support\Carbon|null $end_at
 * @property int|null $days
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read ContractLine|null $contract_line
 * @property-read Invoice|null $invoice
 * @property-read InvoiceLine|null $invoice_line
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLinePaid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLinePaid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLinePaid query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLinePaid whereBeginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLinePaid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLinePaid whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLinePaid whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLinePaid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLinePaid whereIdContractLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLinePaid whereIdInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLinePaid whereIdInvoiceLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLinePaid whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLinePaid whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ContractLinePaidFactory>
 */
class ContractLinePaid extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'contract_line_paid';

    protected $casts = [
        'id_contract_line' => 'int',
        'id_invoice' => 'int',
        'id_invoice_line' => 'int',
        'total' => 'float',
        'begin_at' => 'datetime',
        'end_at' => 'datetime',
        'days' => 'int',
    ];

    protected $fillable = [
        'id_contract_line',
        'id_invoice',
        'id_invoice_line',
        'total',
        'begin_at',
        'end_at',
        'days',
    ];

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
     * @return BelongsTo<InvoiceLine, $this>
     */
    public function invoice_line(): BelongsTo
    {
        return $this->belongsTo(InvoiceLine::class, 'id_invoice_line');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\ContractLinePaidFactory
    {
        return \Database\Factories\ContractLinePaidFactory::new();
    }
}
