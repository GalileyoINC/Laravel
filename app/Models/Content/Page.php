<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Content;

use Database\Factories\ContentPageFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Page
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $status
 * @property array<array-key, mixed>|null $params
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, PageContent> $page_contents
 * @property-read int|null $page_contents_count
 *
 * @method static \Database\Factories\ContentPageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Page extends Model
{
    use HasFactory;

    public const STATUS_OFF = 0;

    public const STATUS_ON = 1;

    protected $table = 'page';

    protected $casts = [
        'status' => 'int',
        'params' => 'json',
    ];

    protected $fillable = [
        'name',
        'slug',
        'status',
        'params',
    ];

    public function page_contents()
    {
        return $this->hasMany(PageContent::class, 'id_page');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ContentPageFactory::new();
    }
}
