<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
 * @property CreditCard|null $credit_card
 * @property Invoice|null $invoice
 * @property MoneyTransaction|null $money_transaction
 * @property User $user
 * @property Collection|LogAuthorize[] $log_authorizes
 * @property Collection|MoneyTransaction[] $money_transactions
 */
class MoneyTransaction extends Model
{
    use HasFactory;

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
        'is_test' => 'bool',
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
        'note',
    ];

    public function credit_card()
    {
        return $this->belongsTo(App\Models\Finance\CreditCard::class, 'id_credit_card');
    }

    public function invoice()
    {
        return $this->belongsTo(App\Models\Finance\Invoice::class, 'id_invoice');
    }

    public function money_transaction()
    {
        return $this->belongsTo(App\Models\Finance\MoneyTransaction::class, 'id_refund');
    }

    public function user()
    {
        return $this->belongsTo(App\Models\User\User::class, 'id_user');
    }

    public function log_authorizes()
    {
        return $this->hasMany(App\Models\Analytics\LogAuthorize::class, 'id_money_transaction');
    }

    public function money_transactions()
    {
        return $this->hasMany(App\Models\Finance\MoneyTransaction::class, 'id_refund');
    }
}
