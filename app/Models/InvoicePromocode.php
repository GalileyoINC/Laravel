<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class InvoicePromocode
 * 
 * @property int $id
 * @property int $id_promo
 * @property int $id_invoice
 * @property Carbon|null $created_at
 * 
 * @property Invoice $invoice
 * @property Promocode $promocode
 *
 * @package App\Models
 */
class InvoicePromocode extends Model
{
	use HasFactory;

	protected $table = 'invoice_promocode';
	public $timestamps = false;

	protected $casts = [
		'id_promo' => 'int',
		'id_invoice' => 'int'
	];

	protected $fillable = [
		'id_promo',
		'id_invoice'
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
