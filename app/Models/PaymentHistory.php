<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PaymentHistory model
 * Represents payment transaction history
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $subscription_id
 * @property int|null $credit_card_id
 * @property string $type
 * @property float $total
 * @property string $title
 * @property bool $is_void
 * @property bool $is_test
 * @property bool $is_success
 * @property string|null $card_number
 * @property int|null $invoice_id
 * @property string|null $external_transaction_id
 * @property array|null $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read User $user
 * @property-read UserSubscription|null $subscription
 * @property-read CreditCard|null $creditCard
 */
class PaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'payment_histories';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'credit_card_id',
        'type',
        'total',
        'title',
        'is_void',
        'is_test',
        'is_success',
        'card_number',
        'invoice_id',
        'external_transaction_id',
        'metadata',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'is_void' => 'boolean',
        'is_test' => 'boolean',
        'is_success' => 'boolean',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the payment history
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subscription for this payment history
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'subscription_id', 'id');
    }

    /**
     * Get the credit card used for this payment
     */
    public function creditCard(): BelongsTo
    {
        return $this->belongsTo(CreditCard::class, 'credit_card_id', 'id');
    }

    /**
     * Scope to get only successful payments
     */
    public function scopeSuccessful($query)
    {
        return $query->where('is_success', true);
    }

    /**
     * Scope to get only failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('is_success', false);
    }

    /**
     * Scope to get payments by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAttribute(): string
    {
        return '$' . number_format($this->total, 2);
    }

    /**
     * Get payment type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'authorize' => 'Credit Card',
            'bitpay' => 'BitPay',
            'apply_credit' => 'Credit Applied',
            'pay_from_credit' => 'Paid from Credit',
            'discount' => 'Discount',
            'apple' => 'Apple Pay',
            default => ucfirst($this->type),
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        if ($this->is_void) {
            return 'gray';
        }

        if ($this->is_test) {
            return 'yellow';
        }

        return $this->is_success ? 'green' : 'red';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute(): string
    {
        if ($this->is_void) {
            return 'Voided';
        }

        if ($this->is_test) {
            return 'Test Payment';
        }

        return $this->is_success ? 'Success' : 'Failed';
    }
}