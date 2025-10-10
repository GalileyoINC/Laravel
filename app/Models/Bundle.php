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
 * Class Bundle
 * 
 * @property int $id
 * @property string|null $title
 * @property int|null $type
 * @property int|null $pay_interval
 * @property bool $is_active
 * @property float $total
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|BundleItem[] $bundle_items
 * @property Collection|InvoiceLine[] $invoice_lines
 *
 * @package App\Models
 */
class Bundle extends Model
{
	use HasFactory;

	protected $table = 'bundle';

	protected $casts = [
		'type' => 'int',
		'pay_interval' => 'int',
		'is_active' => 'bool',
		'total' => 'float'
	];

	protected $fillable = [
		'title',
		'type',
		'pay_interval',
		'is_active',
		'total'
	];

	public function bundle_items()
	{
		return $this->hasMany(BundleItem::class, 'id_bundle');
	}

	public function invoice_lines()
	{
		return $this->hasMany(InvoiceLine::class, 'id_bundle');
	}
}
