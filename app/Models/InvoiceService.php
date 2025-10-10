<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InvoiceService
 * 
 * @property int $id_invoice
 * @property int $id_service
 * 
 * @property Invoice $invoice
 * @property Service $service
 *
 * @package App\Models
 */
class InvoiceService extends Model
{
	protected $table = 'invoice_service';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_invoice' => 'int',
		'id_service' => 'int'
	];

	protected $fillable = [
		'id_invoice',
		'id_service'
	];

	public function invoice()
	{
		return $this->belongsTo(Invoice::class, 'id_invoice');
	}

	public function service()
	{
		return $this->belongsTo(Service::class, 'id_service');
	}
}
