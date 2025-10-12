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
 * @property array|null $full
 */
class MarketstackIndx extends Model
{
    use HasFactory;

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
