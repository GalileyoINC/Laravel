<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ProductPhoto
 *
 * @property int $id
 * @property int|null $id_service
 * @property string|null $folder_name
 * @property array<array-key, mixed>|null $sizes
 * @property bool|null $is_main
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Finance\Service|null $service
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereFolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereIdService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereIsMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereSizes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ContentProductPhotoFactory>
 */
class ProductPhoto extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'product_photo';

    protected $casts = [
        'id_service' => 'int',
        'sizes' => 'json',
        'is_main' => 'bool',
    ];

    protected $fillable = [
        'id_service',
        'folder_name',
        'sizes',
        'is_main',
    ];

    /**
     * @return BelongsTo<\App\Models\Finance\Service, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Finance\Service::class, 'id_service');
    }
}
