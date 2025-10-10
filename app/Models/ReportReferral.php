<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ReportReferral
 * 
 * @property int $id
 * @property string|null $period
 * @property int $influencer_percent
 * @property array|null $data
 * @property Carbon $created_at
 *
 * @package App\Models
 */
class ReportReferral extends Model
{
	use HasFactory;

	protected $table = 'report_referral';
	public $timestamps = false;

	protected $casts = [
		'influencer_percent' => 'int',
		'data' => 'json'
	];

	protected $fillable = [
		'period',
		'influencer_percent',
		'data'
	];
}
