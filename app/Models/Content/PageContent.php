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
 * Class PageContent
 *
 * @property int $id
 * @property int|null $id_page
 * @property int $status
 * @property array<array-key, mixed>|null $params
 * @property string|null $content
 * @property string $created_at
 * @property-read Page|null $page
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageContent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageContent whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageContent whereIdPage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageContent whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PageContent whereStatus($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ContentPageContentFactory>
 */
class PageContent extends Model
{
    use HasFactory; /** @phpstan-ignore-line */ /** @phpstan-ignore-line */

    /** @phpstan-ignore-line */
    public const STATUS_TEMP = 0;

    public const STATUS_PUBLISH = 1;

    /** Backward compatible alias used in controllers */
    public const STATUS_DRAFT = self::STATUS_TEMP;

    /** Alias to match request references */
    public const STATUS_PUBLISHED = self::STATUS_PUBLISH;

    public $timestamps = false;

    protected $table = 'page_content';

    protected $casts = [
        'id_page' => 'int',
        'status' => 'int',
        'params' => 'json',
    ];

    protected $fillable = [
        'id_page',
        'status',
        'params',
        'content',
    ];

    /**
     * @return BelongsTo<Page, $this>
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'id_page');
    }
}
