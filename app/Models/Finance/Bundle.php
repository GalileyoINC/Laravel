<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Database\Factories\BundleFactory as RootBundleFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Bundle
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $type
 * @property int|null $pay_interval
 * @property bool $is_active
 * @property float $total
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, BundleItem> $bundle_items
 * @property-read int|null $bundle_items_count
 * @property-read Collection<int, InvoiceLine> $invoice_lines
 * @property-read int|null $invoice_lines_count
 * @property-read Collection<int, Service> $services
 * @property-read int|null $services_count
 *
 * @method static \Database\Factories\FinanceBundleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bundle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bundle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bundle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bundle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bundle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bundle whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bundle wherePayInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bundle whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bundle whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bundle whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bundle whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\FinanceBundleFactory>
 */
class Bundle extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'bundle';

    protected $casts = [
        'type' => 'int',
        'pay_interval' => 'int',
        'is_active' => 'bool',
        'total' => 'float',
    ];

    protected $fillable = [
        'title',
        'type',
        'pay_interval',
        'is_active',
        'total',
    ];

    /**
     * @return BelongsToMany<Service, $this>
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'bundle_items', 'id_bundle', 'id_service');
    }

    /**
     * @return HasMany<BundleItem, $this>
     */
    public function bundle_items(): HasMany
    {
        return $this->hasMany(BundleItem::class, 'id_bundle');
    }

    /**
     * @return HasMany<InvoiceLine, $this>
     */
    public function invoice_lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class, 'id_bundle');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): RootBundleFactory
    {
        return RootBundleFactory::new();
    }
}
