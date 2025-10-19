<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

use App\Models\Finance\Promocode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PromocodeInfluencer
 *
 * @property int $id_promocode
 * @property-read Promocode $promocode
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromocodeInfluencer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromocodeInfluencer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromocodeInfluencer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromocodeInfluencer whereIdPromocode($value)
 *
 * @mixin \Eloquent
 */
class PromocodeInfluencer extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'promocode_influencer';

    protected $primaryKey = 'id_promocode';

    protected $casts = [
        'id_promocode' => 'int',
    ];

    public function promocode()
    {
        return $this->belongsTo(Promocode::class, 'id_promocode');
    }
}
