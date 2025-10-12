<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Content;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class News
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $image
 * @property int $status
 * @property array|null $params
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property Collection|NewsContent[] $news_contents
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

    public function news_contents()
    {
        return $this->hasMany(NewsContent::class, 'id_news');
    }
}
