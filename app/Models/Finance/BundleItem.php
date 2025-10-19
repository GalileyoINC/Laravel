<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BundleItem
 *
 * @property int $id
 * @property int|null $id_bundle
 * @property int|null $id_item
 * @property int|null $type
 * @property float $price
 * @property int|null $quantity
 * @property array<array-key, mixed>|null $custom_data
 * @property-read Bundle|null $bundle
 * @property-read Service|null $service
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BundleItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BundleItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BundleItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BundleItem whereCustomData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BundleItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BundleItem whereIdBundle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BundleItem whereIdItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BundleItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BundleItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BundleItem whereType($value)
 *
 * @mixin \Eloquent
 */
class BundleItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'bundle_item';

    protected $casts = [
        'id_bundle' => 'int',
        'id_item' => 'int',
        'type' => 'int',
        'price' => 'float',
        'quantity' => 'int',
        'custom_data' => 'json',
    ];

    protected $fillable = [
        'id_bundle',
        'id_item',
        'type',
        'price',
        'quantity',
        'custom_data',
    ];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'id_bundle');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_item');
    }
}
