<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Content;

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
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Podcast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Podcast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Podcast query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Podcast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Podcast whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Podcast whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Podcast whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Podcast whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Podcast whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Podcast whereUrl($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\PodcastFactory>
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
