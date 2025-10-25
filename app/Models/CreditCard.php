<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * CreditCard model
 * Represents user credit cards for payment processing
 *
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $num
 * @property string|null $phone
 * @property string|null $zip
 * @property string|null $cvv
 * @property string|null $type
 * @property int $expiration_year
 * @property int $expiration_month
 * @property bool $is_active
 * @property bool $is_preferred
 * @property bool $is_agree_to_receive
 * @property string|null $anet_customer_payment_profile_id
 * @property bool $anet_profile_deleted
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<PaymentHistory> $paymentHistories
 * @property-read \Illuminate\Database\Eloquent\Collection<Subscription> $subscriptions
 */
class CreditCard extends Model
{
    use HasFactory;

    protected $table = 'credit_cards';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'num',
        'phone',
        'zip',
        'cvv',
        'type',
        'expiration_year',
        'expiration_month',
        'is_active',
        'is_preferred',
        'is_agree_to_receive',
        'anet_customer_payment_profile_id',
        'anet_profile_deleted',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_preferred' => 'boolean',
        'is_agree_to_receive' => 'boolean',
        'anet_profile_deleted' => 'boolean',
        'expiration_year' => 'integer',
        'expiration_month' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the credit card
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the payment histories for this credit card
     */
    public function paymentHistories(): HasMany
    {
        return $this->hasMany(PaymentHistory::class, 'credit_card_id', 'id');
    }

    /**
     * Get the subscriptions using this credit card
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class, 'credit_card_id', 'id');
    }

    /**
     * Scope to get only active credit cards
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get preferred credit card
     */
    public function scopePreferred($query)
    {
        return $query->where('is_preferred', true);
    }

    /**
     * Get masked card number for display
     */
    public function getMaskedNumberAttribute(): string
    {
        if (strlen($this->num) <= 4) {
            return $this->num;
        }
        
        return str_repeat('*', strlen($this->num) - 4) . substr($this->num, -4);
    }

    /**
     * Get formatted expiration date
     */
    public function getFormattedExpirationAttribute(): string
    {
        return sprintf('%02d/%d', $this->expiration_month, $this->expiration_year);
    }

    /**
     * Check if card is expired
     */
    public function isExpired(): bool
    {
        $now = now();
        $expirationDate = \Carbon\Carbon::createFromDate($this->expiration_year, $this->expiration_month, 1)->endOfMonth();
        
        return $now->gt($expirationDate);
    }
}