<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InvoicePromocode
 *
 * @property int $id
 * @property int $id_promo
 * @property int $id_invoice
 * @property string|null $created_at
 * @property-read Invoice $invoice
 * @property-read Promocode $promocode
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoicePromocode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoicePromocode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoicePromocode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoicePromocode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoicePromocode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoicePromocode whereIdInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoicePromocode whereIdPromo($value)
 *
 * @mixin \Eloquent
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
        return $this->belongsTo(Invoice::class, 'id_invoice');
    }

    public function promocode()
    {
        return $this->belongsTo(Promocode::class, 'id_promo');
    }
}
