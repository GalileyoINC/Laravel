<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MarketstackIndx
 *
 * @property int $id
 * @property string $name
 * @property string $symbol
 * @property string|null $country
 * @property string|null $currency
 * @property bool|null $has_intraday
 * @property bool|null $has_eod
 * @property bool|null $is_active
 * @property array<array-key, mixed>|null $full
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketstackIndx newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketstackIndx newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketstackIndx query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketstackIndx whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketstackIndx whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketstackIndx whereFull($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketstackIndx whereHasEod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketstackIndx whereHasIntraday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketstackIndx whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketstackIndx whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketstackIndx whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketstackIndx whereSymbol($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\FinanceMarketstackIndxFactory>
 */
class MarketstackIndx extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'marketstack_indx';

    protected $casts = [
        'has_intraday' => 'bool',
        'has_eod' => 'bool',
        'is_active' => 'bool',
        'full' => 'json',
    ];

    protected $fillable = [
        'name',
        'symbol',
        'country',
        'currency',
        'has_intraday',
        'has_eod',
        'is_active',
        'full',
    ];
}
