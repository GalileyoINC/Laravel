<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $is_void
 * @property int|null $id_refund
 * @property bool $is_test
 * @property string|null $error
 * @property string|null $note
 * @property-read CreditCard|null $creditCard
 * @property-read CreditCard|null $credit_card
 * @property-read Invoice|null $invoice
 * @property-read Collection<int, \App\Models\Analytics\LogAuthorize> $log_authorizes
 * @property-read int|null $log_authorizes_count
 * @property-read MoneyTransaction|null $money_transaction
 * @property-read Collection<int, MoneyTransaction> $money_transactions
 * @property-read int|null $money_transactions_count
 * @property-read \App\Models\User\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction whereIdCreditCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction whereIdInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction whereIdRefund($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction whereIsSuccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction whereIsTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction whereIsVoid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction whereTransactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyTransaction whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\MoneyTransactionFactory>
 */
class MoneyTransaction extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
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

    /**
     * @return BelongsTo<CreditCard, $this>
     */
    public function credit_card(): BelongsTo
    {
        return $this->belongsTo(CreditCard::class, 'id_credit_card');
    }

    // Alias used by controllers/views
    /**
     * @return BelongsTo<CreditCard, $this>
     */
    public function creditCard(): BelongsTo
    {
        return $this->belongsTo(CreditCard::class, 'id_credit_card');
    }

    /**
     * @return BelongsTo<Invoice, $this>
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'id_invoice');
    }

    /**
     * @return BelongsTo<MoneyTransaction, $this>
     */
    public function money_transaction(): BelongsTo
    {
        return $this->belongsTo(self::class, 'id_refund');
    }

    /**
     * @return BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Analytics\LogAuthorize, $this>
     */
    public function log_authorizes(): HasMany
    {
        return $this->hasMany(\App\Models\Analytics\LogAuthorize::class, 'id_money_transaction');
    }

    /**
     * @return HasMany<MoneyTransaction, $this>
     */
    public function money_transactions(): HasMany
    {
        return $this->hasMany(self::class, 'id_refund');
    }

    /**
     * Determine if the transaction can be refunded.
     * Conservative default: only successful, not voided, and has total > 0.
     */
    public function canBeRefund(): bool
    {
        return (bool) ($this->is_success && ! $this->is_void && ($this->total ?? 0) > 0);
    }

    /**
     * Determine if the transaction can be voided.
     * Conservative default: only successful and not already voided.
     */
    public function canBeVoided(): bool
    {
        return (bool) ($this->is_success && ! $this->is_void);
    }

    /**
     * Void the transaction
     */
    public function void(): bool
    {
        if (! $this->canBeVoided()) {
            return false;
        }

        $this->is_void = true;

        return $this->save();
    }
}
