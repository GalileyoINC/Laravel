<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NewsContent
 *
 * @property int $id
 * @property int|null $id_news
 * @property int $status
 * @property array<array-key, mixed>|null $params
 * @property string|null $content
 * @property string $created_at
 * @property-read News|null $news
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsContent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsContent whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsContent whereIdNews($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsContent whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsContent whereStatus($value)
 *
 * @mixin \Eloquent
 */
class NewsContent extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'news_content';

    protected $casts = [
        'id_news' => 'int',
        'status' => 'int',
        'params' => 'json',
    ];

    protected $fillable = [
        'id_news',
        'status',
        'params',
        'content',
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'id_news');
    }
}
