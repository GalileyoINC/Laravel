<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Modules\Content\Infrastructure\Models\Content;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Podcast
 *
 * @property int $id
 * @property int $type
 * @property string|null $title
 * @property string|null $url
 * @property string|null $image
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class Podcast extends Model
{
    use HasFactory;

    protected $table = 'podcast';

    protected $casts = [
        'type' => 'int',
    ];

    protected $fillable = [
        'type',
        'title',
        'url',
        'image',
    ];
}
