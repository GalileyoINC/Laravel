<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InvoicePromocode
 *
 * @property int $id
 * @property int $id_promo
 * @property int $id_invoice
 * @property Carbon|null $created_at
 * @property Invoice $invoice
 * @property Promocode $promocode
 */
class InvoicePromocode extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'invoice_promocode';

    protected $casts = [
        'id_promo' => 'int',
        'id_invoice' => 'int',
    ];

    protected $fillable = [
        'id_promo',
        'id_invoice',
    ];

    public function invoice()
    {
        return $this->belongsTo(\App\Models\Finance\Invoice::class, 'id_invoice');
    }

    public function promocode()
    {
        return $this->belongsTo(Promocode::class, 'id_promo');
    }
}
