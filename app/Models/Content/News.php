<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Content;

use Database\Factories\NewsFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class News
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $image
 * @property int $status
 * @property array<array-key, mixed>|null $params
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, NewsContent> $news_contents
 * @property-read int|null $news_contents_count
 *
 * @method static \Database\Factories\NewsFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ContentNewsFactory>
 */
class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $casts = [
        'status' => 'int',
        'params' => 'json',
    ];

    protected $fillable = [
        'name',
        'slug',
        'image',
        'status',
        'params',
    ];

    /**
     * @return HasMany<\App\Models\Content\NewsContent, $this>
     */
    public function news_contents(): HasMany
    {
        return $this->hasMany(NewsContent::class, 'id_news');
    }

    protected static function newFactory()
    {
        return NewsFactory::new();
    }
}
