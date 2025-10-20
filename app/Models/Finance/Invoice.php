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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Invoice
 *
 * @property int $id
 * @property int $id_user
 * @property int|null $id_bp_subscribe
 * @property bool $paid_status
 * @property float $total
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\User\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read \App\Models\Subscription\BpSubscription|null $bp_subscription
 * @property-read Collection<int, ContractLinePaid> $contract_line_paids
 * @property-read int|null $contract_line_paids_count
 * @property-read Collection<int, \App\Models\User\Affiliate> $invite_affiliates
 * @property-read int|null $invite_affiliates_count
 * @property-read Collection<int, InvoiceLine> $invoiceLines
 * @property-read int|null $invoice_lines_count
 * @property-read Collection<int, InvoiceLine> $invoice_lines
 * @property-read Collection<int, MoneyTransaction> $moneyTransactions
 * @property-read int|null $money_transactions_count
 * @property-read Collection<int, MoneyTransaction> $money_transactions
 * @property-read Collection<int, Promocode> $promocodes
 * @property-read int|null $promocodes_count
 * @property-read Collection<int, Service> $services
 * @property-read int|null $services_count
 * @property-read \App\Models\User\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereIdBpSubscribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice wherePaidStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\FinanceInvoiceFactory>
 */
class Invoice extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public const PAY_STATUS_NONE = 0;

    public const PAY_STATUS_SUCCESS = 1;

    public const PAY_STATUS_BEGIN = 8;

    public const PAY_STATUS_ABORT = 9;

    protected $table = 'invoice';

    protected $casts = [
        'id_user' => 'int',
        'id_bp_subscribe' => 'int',
        'paid_status' => 'bool',
        'total' => 'float',
    ];

    protected $fillable = [
        'id_user',
        'id_bp_subscribe',
        'paid_status',
        'total',
        'description',
    ];

    /**
     * @return BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    /**
     * @return BelongsTo<\App\Models\Subscription\BpSubscription, $this>
     */
    public function bp_subscription(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Subscription\BpSubscription::class, 'id_bp_subscribe');
    }

    /**
     * @return HasMany<\App\Models\User\Address, $this>
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(\App\Models\User\Address::class, 'id_invoice');
    }

    /**
     * @return HasMany<ContractLinePaid, $this>
     */
    public function contract_line_paids(): HasMany
    {
        return $this->hasMany(ContractLinePaid::class, 'id_invoice');
    }

    /**
     * @return HasMany<\App\Models\User\Affiliate, $this>
     */
    public function invite_affiliates(): HasMany
    {
        return $this->hasMany(\App\Models\User\Affiliate::class, 'id_reward_invoice');
    }

    /**
     * @return HasMany<InvoiceLine, $this>
     */
    public function invoice_lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class, 'id_invoice');
    }

    /**
     * @return BelongsToMany<Promocode, $this>
     */
    public function promocodes(): BelongsToMany
    {
        return $this->belongsToMany(Promocode::class, 'invoice_promocode', 'id_invoice', 'id_promo')
            ->withPivot('id');
    }

    /**
     * @return BelongsToMany<Service, $this>
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'invoice_service', 'id_invoice', 'id_service');
    }

    /**
     * @return HasMany<MoneyTransaction, $this>
     */
    public function money_transactions(): HasMany
    {
        return $this->hasMany(MoneyTransaction::class, 'id_invoice');
    }

    /**
     * Get money transactions (camelCase alias)
     *
     * @return HasMany<MoneyTransaction, $this>
     */
    public function moneyTransactions(): HasMany
    {
        return $this->money_transactions();
    }

    /**
     * Get invoice lines (camelCase alias)
     *
     * @return HasMany<InvoiceLine, $this>
     */
    public function invoiceLines(): HasMany
    {
        return $this->invoice_lines();
    }
}
