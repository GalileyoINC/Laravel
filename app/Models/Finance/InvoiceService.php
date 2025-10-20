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
 * Class InvoiceService
 *
 * @property int $id_invoice
 * @property int $id_service
 * @property-read Invoice $invoice
 * @property-read Service $service
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceService query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceService whereIdInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceService whereIdService($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\InvoiceServiceFactory>
 */
class InvoiceService extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'invoice_service';

    protected $casts = [
        'id_invoice' => 'int',
        'id_service' => 'int',
    ];

    protected $fillable = [
        'id_invoice',
        'id_service',
    ];

    /**
     * @return BelongsTo<Invoice, $this>
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'id_invoice');
    }

    /**
     * @return BelongsTo<Service, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'id_service');
    }
}
