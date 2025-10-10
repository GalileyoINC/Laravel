<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContractLinePaid
 * 
 * @property int $id
 * @property int|null $id_contract_line
 * @property int|null $id_invoice
 * @property int|null $id_invoice_line
 * @property float $total
 * @property Carbon|null $begin_at
 * @property Carbon|null $end_at
 * @property int|null $days
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ContractLine|null $contract_line
 * @property Invoice|null $invoice
 * @property InvoiceLine|null $invoice_line
 *
 * @package App\Models
 */
class ContractLinePaid extends Model
{
	use HasFactory;

	protected $table = 'contract_line_paid';

	protected $casts = [
		'id_contract_line' => 'int',
		'id_invoice' => 'int',
		'id_invoice_line' => 'int',
		'total' => 'float',
		'begin_at' => 'datetime',
		'end_at' => 'datetime',
		'days' => 'int'
	];

	protected $fillable = [
		'id_contract_line',
		'id_invoice',
		'id_invoice_line',
		'total',
		'begin_at',
		'end_at',
		'days'
	];

	public function contract_line()
	{
		return $this->belongsTo(ContractLine::class, 'id_contract_line');
	}

	public function invoice()
	{
		return $this->belongsTo(Invoice::class, 'id_invoice');
	}

	public function invoice_line()
	{
		return $this->belongsTo(InvoiceLine::class, 'id_invoice_line');
	}
}
