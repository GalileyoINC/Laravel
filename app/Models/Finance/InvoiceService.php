<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InvoiceService
 *
 * @property int $id_invoice
 * @property int $id_service
 * @property Invoice $invoice
 * @property Service $service
 */
class InvoiceService extends Model
{
    use HasFactory;

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

    public function invoice()
    {
        return $this->belongsTo(\App\Models\Finance\Invoice::class, 'id_invoice');
    }

    public function service()
    {
        return $this->belongsTo(\App\Models\Finance\Service::class, 'id_service');
    }
}
