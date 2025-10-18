<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Content;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductPhoto
 *
 * @property int $id
 * @property int|null $id_service
 * @property string|null $folder_name
 * @property array|null $sizes
 * @property bool|null $is_main
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property Service|null $service
 */
class ProductPhoto extends Model
{
    use HasFactory;

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

    public function service()
    {
        return $this->belongsTo(\App\Models\Finance\Service::class, 'id_service');
    }
}
