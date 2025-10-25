<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * UserSubscription model
 * Represents user subscriptions to products/plans
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int|null $credit_card_id
 * @property string $status
 * @property float $price
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon|null $end_date
 * @property \Carbon\Carbon|null $cancel_at
 * @property bool $is_cancelled
 * @property bool $can_reactivate
 * @property array|null $settings
 * @property string|null $payment_method
 * @property string|null $external_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read User $user
 * @property-read Product $product
 * @property-read CreditCard|null $creditCard
 * @property-read \Illuminate\Database\Eloquent\Collection<PaymentHistory> $paymentHistories
 */
class UserSubscription extends Model
{
    use HasFactory;

    protected $table = 'user_subscriptions';

    protected $fillable = [
        'user_id',
        'product_id',
        'credit_card_id',
        'status',
        'price',
        'start_date',
        'end_date',
        'cancel_at',
        'is_cancelled',
        'can_reactivate',
        'settings',
        'payment_method',
        'external_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'cancel_at' => 'date',
        'is_cancelled' => 'boolean',
        'can_reactivate' => 'boolean',
        'settings' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the subscription
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product for this subscription
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get the credit card used for this subscription
     */
    public function creditCard(): BelongsTo
    {
        return $this->belongsTo(CreditCard::class, 'credit_card_id', 'id');
    }

    /**
     * Get the payment histories for this subscription
     */
    public function paymentHistories(): HasMany
    {
        return $this->hasMany(PaymentHistory::class);
    }

    /**
     * Scope to get only active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get cancelled subscriptions
     */
    public function scopeCancelled($query)
    {
        return $query->where('is_cancelled', true);
    }

    /**
     * Scope to get current subscriptions
     */
    public function scopeCurrent($query)
    {
        return $query->where('status', 'active')
                    ->where('is_cancelled', false)
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>', now());
                    });
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               !$this->is_cancelled && 
               (!$this->end_date || $this->end_date->isFuture());
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->end_date && $this->end_date->isPast();
    }

    /**
     * Check if subscription can be reactivated
     */
    public function canReactivate(): bool
    {
        if (!$this->is_cancelled) {
            return false;
        }

        if (!$this->cancel_at) {
            return false;
        }

        // Can reactivate within 6 months of cancellation
        return $this->cancel_at->addMonths(6)->isFuture();
    }

    /**
     * Get days until expiration
     */
    public function getDaysUntilExpirationAttribute(): ?int
    {
        if (!$this->end_date) {
            return null;
        }

        return now()->diffInDays($this->end_date, false);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }
}