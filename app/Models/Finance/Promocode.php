<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use App\Models\Subscription\PromocodeInfluencer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Promocode
 *
 * @property int $id
 * @property int $type
 * @property string $text
 * @property int $discount
 * @property int $is_active
 * @property \Illuminate\Support\Carbon $active_from
 * @property \Illuminate\Support\Carbon $active_to
 * @property int|null $trial_period
 * @property bool $show_on_frontend
 * @property string|null $description
 * @property-read PromocodeInfluencer|null $influencer
 * @property-read Collection<int, Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read PromocodeInfluencer|null $promocode_influencer
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocode whereActiveFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocode whereActiveTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocode whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocode whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocode whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocode whereShowOnFrontend($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocode whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocode whereTrialPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocode whereType($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\PromocodeFactory>
 */
class Promocode extends Model
{
    use HasFactory;

    // Legacy-compatible constants
    public const TYPE_UNIVERSAL = 0;

    public const TYPE_INDIVIDUAL = 1;

    public const TYPE_FAMILY = 2;

    public const TYPE_BUSINESS = 3;

    public const TYPE_CUSTOM = 4;

    public const TYPE_INFLUENCER = 5;

    public const TYPE_TEST = 6;

    public const TYPE_BASIC = 7;

    public const TYPE_FRIENDS = 8;

    public const TYPE_CUSTOM_WITH_SATELLITE = 10;

    public const TYPE_CUSTOM_WITHOUT_SATELLITE = 11;

    // Used by controllers; align to influencer type
    public const TYPE_SALE = self::TYPE_INFLUENCER;

    public const STATUS_ACTIVE = 1;

    public const STATUS_NON_ACTIVE = 0;

    public $timestamps = false;

    protected $table = 'promocode';

    protected $casts = [
        'type' => 'int',
        'discount' => 'int',
        'is_active' => 'int',
        'active_from' => 'datetime',
        'active_to' => 'datetime',
        'trial_period' => 'int',
        'show_on_frontend' => 'bool',
    ];

    protected $fillable = [
        'type',
        'text',
        'discount',
        'is_active',
        'active_from',
        'active_to',
        'trial_period',
        'show_on_frontend',
        'description',
    ];

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class, 'invoice_promocode', 'id_promo', 'id_invoice')
            ->withPivot('id');
    }

    public function promocode_influencer(): HasOne
    {
        return $this->hasOne(PromocodeInfluencer::class, 'id_promocode');
    }

    // Alias to satisfy controllers using ->with(['influencer'])
    public function influencer(): HasOne
    {
        return $this->hasOne(PromocodeInfluencer::class, 'id_promocode');
    }
}
