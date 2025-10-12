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
 * Class NewsContent
 *
 * @property int $id
 * @property int|null $id_news
 * @property int $status
 * @property array|null $params
 * @property string|null $content
 * @property Carbon $created_at
 * @property News|null $news
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
