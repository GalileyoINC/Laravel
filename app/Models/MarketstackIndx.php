<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

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
 *
 * @package App\Models
 */
class MarketstackIndx extends Model
{
	protected $table = 'marketstack_indx';
	public $timestamps = false;

	protected $casts = [
		'has_intraday' => 'bool',
		'has_eod' => 'bool',
		'is_active' => 'bool',
		'full' => 'json'
	];

	protected $fillable = [
		'name',
		'symbol',
		'country',
		'currency',
		'has_intraday',
		'has_eod',
		'is_active',
		'full'
	];
}
