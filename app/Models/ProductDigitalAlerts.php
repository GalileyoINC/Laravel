<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ProductDigitalAlerts model
 * Represents digital alerts for products
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductDigitalAlerts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductDigitalAlerts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductDigitalAlerts query()
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ProductDigitalAlertsFactory>
 */
class ProductDigitalAlerts extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'product_digital_alerts';

    protected $fillable = [
        'type',
        'status',
        'title',
        'description',
        'alert_data',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'type' => 'int',
        'status' => 'int',
        'alert_data' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return $this->table ?? 'product_digital_alerts';
    }
}
