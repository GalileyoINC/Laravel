<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceCustom
 *
 * @property int $id
 * @property float $phone_price
 * @property float $feed_price
 * @property int $phone_min
 * @property int $phone_max
 * @property int $feed_min
 * @property int $feed_max
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCustom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCustom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCustom query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCustom whereFeedMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCustom whereFeedMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCustom whereFeedPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCustom whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCustom wherePhoneMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCustom wherePhoneMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCustom wherePhonePrice($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ServiceCustomFactory>
 */
class ServiceCustom extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'service_custom';

    protected $casts = [
        'phone_price' => 'float',
        'feed_price' => 'float',
        'phone_min' => 'int',
        'phone_max' => 'int',
        'feed_min' => 'int',
        'feed_max' => 'int',
    ];

    protected $fillable = [
        'phone_price',
        'feed_price',
        'phone_min',
        'phone_max',
        'feed_min',
        'feed_max',
    ];
}
