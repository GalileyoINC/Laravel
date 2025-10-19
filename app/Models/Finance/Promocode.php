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
use App\Models\Subscription\PromocodeInfluencer;

/**
 * Class Promocode
 *
 * @property int $id
 * @property int $type
 * @property string $text
 * @property int $discount
 * @property int $is_active
 * @property Carbon $active_from
 * @property Carbon $active_to
 * @property int|null $trial_period
 * @property bool $show_on_frontend
 * @property string|null $description
 * @property Collection|Invoice[] $invoices
 * @property PromocodeInfluencer|null $promocode_influencer
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

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_promocode', 'id_promo', 'id_invoice')
            ->withPivot('id');
    }

    public function promocode_influencer()
    {
        return $this->hasOne(PromocodeInfluencer::class, 'id_promocode');
    }

    // Alias to satisfy controllers using ->with(['influencer'])
    public function influencer()
    {
        return $this->hasOne(PromocodeInfluencer::class, 'id_promocode');
    }
}
