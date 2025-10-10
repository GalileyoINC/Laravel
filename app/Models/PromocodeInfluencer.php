<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PromocodeInfluencer
 * 
 * @property int $id_promocode
 * 
 * @property Promocode $promocode
 *
 * @package App\Models
 */
class PromocodeInfluencer extends Model
{
	use HasFactory;

	protected $table = 'promocode_influencer';
	protected $primaryKey = 'id_promocode';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_promocode' => 'int'
	];

	public function promocode()
	{
		return $this->belongsTo(Promocode::class, 'id_promocode');
	}
}
