<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MoneyTransaction
 * 
 * @property int $id
 * @property int $id_user
 * @property int|null $id_invoice
 * @property int|null $id_credit_card
 * @property int $transaction_type
 * @property string|null $transaction_id
 * @property bool|null $is_success
 * @property float $total
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property bool $is_void
 * @property int|null $id_refund
 * @property bool $is_test
 * @property string|null $error
 * @property string|null $note
 * 
 * @property CreditCard|null $credit_card
 * @property Invoice|null $invoice
 * @property MoneyTransaction|null $money_transaction
 * @property User $user
 * @property Collection|LogAuthorize[] $log_authorizes
 * @property Collection|MoneyTransaction[] $money_transactions
 *
 * @package App\Models
 */
class MoneyTransaction extends Model
{
	protected $table = 'money_transaction';

	protected $casts = [
		'id_user' => 'int',
		'id_invoice' => 'int',
		'id_credit_card' => 'int',
		'transaction_type' => 'int',
		'is_success' => 'bool',
		'total' => 'float',
		'is_void' => 'bool',
		'id_refund' => 'int',
		'is_test' => 'bool'
	];

	protected $fillable = [
		'id_user',
		'id_invoice',
		'id_credit_card',
		'transaction_type',
		'transaction_id',
		'is_success',
		'total',
		'is_void',
		'id_refund',
		'is_test',
		'error',
		'note'
	];

	public function credit_card()
	{
		return $this->belongsTo(CreditCard::class, 'id_credit_card');
	}

	public function invoice()
	{
		return $this->belongsTo(Invoice::class, 'id_invoice');
	}

	public function money_transaction()
	{
		return $this->belongsTo(MoneyTransaction::class, 'id_refund');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}

	public function log_authorizes()
	{
		return $this->hasMany(LogAuthorize::class, 'id_money_transaction');
	}

	public function money_transactions()
	{
		return $this->hasMany(MoneyTransaction::class, 'id_refund');
	}
}
