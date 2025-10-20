<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Finance\CreditCard;
use App\Models\Finance\Service;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $id_user
 * @property int $id_product
 * @property int $quantity
 * @property float $total_amount
 * @property string $payment_method
 * @property string $status
 * @property bool $is_paid
 * @property string|null $notes
 * @property array<array-key, mixed>|null $product_details
 * @property int|null $id_credit_card
 * @property string|null $payment_reference
 * @property array<array-key, mixed>|null $payment_details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read CreditCard|null $creditCard
 * @property-read Service $product
 * @property-read User $user
 *
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIdCreditCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIdProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereProductDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\OrderFactory>
 */
class Order extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'orders';

    protected $casts = [
        'id_user' => 'int',
        'id_product' => 'int',
        'quantity' => 'int',
        'total_amount' => 'float',
        'is_paid' => 'bool',
        'product_details' => 'json',
        'id_credit_card' => 'int',
        'payment_details' => 'json',
    ];

    protected $fillable = [
        'id_user',
        'id_product',
        'quantity',
        'total_amount',
        'payment_method',
        'status',
        'is_paid',
        'notes',
        'product_details',
        'id_credit_card',
        'payment_reference',
        'payment_details',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * @return BelongsTo<Service, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'id_product');
    }

    /**
     * @return BelongsTo<CreditCard, $this>
     */
    public function creditCard(): BelongsTo
    {
        return $this->belongsTo(CreditCard::class, 'id_credit_card');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\OrderFactory
    {
        return \Database\Factories\OrderFactory::new();
    }
}
