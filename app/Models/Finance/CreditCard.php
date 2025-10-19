<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use App\Models\User\User;
use Database\Factories\CreditCardFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class CreditCard
 *
 * @property int $id
 * @property int|null $id_user
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $num
 * @property string|null $cvv
 * @property string|null $type
 * @property int|null $expiration_year
 * @property int|null $expiration_month
 * @property bool $is_active
 * @property int $is_preferred
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $anet_customer_payment_profile_id
 * @property int $anet_profile_deleted
 * @property string|null $phone
 * @property string|null $zip
 * @property bool $is_agree_to_receive
 * @property array<array-key, mixed>|null $billing_address
 * @property-read Collection<int, MoneyTransaction> $money_transactions
 * @property-read int|null $money_transactions_count
 * @property-read User|null $user
 *
 * @method static \Database\Factories\CreditCardFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereAnetCustomerPaymentProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereAnetProfileDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereBillingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereCvv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereExpirationMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereExpirationYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereIsAgreeToReceive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereIsPreferred($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditCard whereZip($value)
 *
 * @mixin \Eloquent
 */
class CreditCard extends Model
{
    use HasFactory;

    protected $table = 'credit_card';

    protected $casts = [
        'id_user' => 'int',
        'expiration_year' => 'int',
        'expiration_month' => 'int',
        'is_active' => 'bool',
        'is_preferred' => 'int',
        'anet_profile_deleted' => 'int',
        'is_agree_to_receive' => 'bool',
        'billing_address' => 'json',
    ];

    protected $fillable = [
        'id_user',
        'first_name',
        'last_name',
        'num',
        'cvv',
        'type',
        'expiration_year',
        'expiration_month',
        'is_active',
        'is_preferred',
        'anet_customer_payment_profile_id',
        'anet_profile_deleted',
        'phone',
        'zip',
        'is_agree_to_receive',
        'billing_address',
        'card_number',
        'cardholder_name',
        'expiry_month',
        'expiry_year',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function money_transactions(): HasMany
    {
        return $this->hasMany(MoneyTransaction::class, 'id_credit_card');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return CreditCardFactory::new();
    }
}
