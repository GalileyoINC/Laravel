<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ReportArchive
 * 
 * @property int $id
 * @property string $report_id
 * @property string $name
 * @property string $data
 * @property Carbon $created_at
 * @property string|null $sort
 *
 * @package App\Models
 */
class ReportArchive extends Model
{
	use HasFactory;

	protected $table = 'report_archive';
	public $timestamps = false;

	protected $fillable = [
		'report_id',
		'name',
		'data',
		'sort'
	];
}
