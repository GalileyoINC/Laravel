<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Invoice
 * 
 * @property int $id
 * @property int $id_user
 * @property int|null $id_bp_subscribe
 * @property bool $paid_status
 * @property float $total
 * @property string|null $description
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property BpSubscription|null $bp_subscription
 * @property Collection|Address[] $addresses
 * @property Collection|ContractLinePaid[] $contract_line_paids
 * @property Collection|InviteAffiliate[] $invite_affiliates
 * @property Collection|InvoiceLine[] $invoice_lines
 * @property Collection|Promocode[] $promocodes
 * @property Collection|Service[] $services
 * @property Collection|MoneyTransaction[] $money_transactions
 *
 * @package App\Models
 */
class Invoice extends Model
{
	use HasFactory;

	protected $table = 'invoice';

	protected $casts = [
		'id_user' => 'int',
		'id_bp_subscribe' => 'int',
		'paid_status' => 'bool',
		'total' => 'float'
	];

	protected $fillable = [
		'id_user',
		'id_bp_subscribe',
		'paid_status',
		'total',
		'description'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}

	public function bp_subscription()
	{
		return $this->belongsTo(BpSubscription::class, 'id_bp_subscribe');
	}

	public function addresses()
	{
		return $this->hasMany(Address::class, 'id_invoice');
	}

	public function contract_line_paids()
	{
		return $this->hasMany(ContractLinePaid::class, 'id_invoice');
	}

	public function invite_affiliates()
	{
		return $this->hasMany(InviteAffiliate::class, 'id_reward_invoice');
	}

	public function invoice_lines()
	{
		return $this->hasMany(InvoiceLine::class, 'id_invoice');
	}

	public function promocodes()
	{
		return $this->belongsToMany(Promocode::class, 'invoice_promocode', 'id_invoice', 'id_promo')
					->withPivot('id');
	}

	public function services()
	{
		return $this->belongsToMany(Service::class, 'invoice_service', 'id_invoice', 'id_service');
	}

	public function money_transactions()
	{
		return $this->hasMany(MoneyTransaction::class, 'id_invoice');
	}
}
