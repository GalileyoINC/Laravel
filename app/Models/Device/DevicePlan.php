<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Device;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DevicePlan
 *
 * @property int $id
 * @property int|null $id_device
 * @property int|null $id_plan
 * @property bool $is_default
 * @property float|null $price
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Finance\Service|null $service
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevicePlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevicePlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevicePlan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevicePlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevicePlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevicePlan whereIdDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevicePlan whereIdPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevicePlan whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevicePlan wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevicePlan whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class DevicePlan extends Model
{
    use HasFactory;

    protected $table = 'device_plan';

    protected $casts = [
        'id_device' => 'int',
        'id_plan' => 'int',
        'is_default' => 'bool',
        'price' => 'float',
    ];

    protected $fillable = [
        'id_device',
        'id_plan',
        'is_default',
        'price',
    ];

    public function service()
    {
        return $this->belongsTo(\App\Models\Finance\Service::class, 'id_plan');
    }
}
