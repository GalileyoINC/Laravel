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
 * Class PageContent
 *
 * @property int $id
 * @property int|null $id_page
 * @property int $status
 * @property array|null $params
 * @property string|null $content
 * @property Carbon $created_at
 * @property Page|null $page
 */
class PageContent extends Model
{
    use HasFactory;

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

    public function page()
    {
        return $this->belongsTo(Page::class, 'id_page');
    }
}
