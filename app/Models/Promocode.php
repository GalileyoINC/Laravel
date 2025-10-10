<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
 * 
 * @property Collection|Invoice[] $invoices
 * @property PromocodeInfluencer|null $promocode_influencer
 *
 * @package App\Models
 */
class Promocode extends Model
{
	use HasFactory;

	protected $table = 'promocode';
	public $timestamps = false;

	protected $casts = [
		'type' => 'int',
		'discount' => 'int',
		'is_active' => 'int',
		'active_from' => 'datetime',
		'active_to' => 'datetime',
		'trial_period' => 'int',
		'show_on_frontend' => 'bool'
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
		'description'
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
}
