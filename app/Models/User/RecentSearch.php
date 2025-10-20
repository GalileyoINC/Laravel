<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RecentSearch
 *
 * @property int $id
 * @property int $id_user
 * @property string|null $phrase
 * @property int|null $id_search_user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentSearch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentSearch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentSearch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentSearch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentSearch whereIdSearchUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentSearch whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentSearch wherePhrase($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\RecentSearchFactory>
 */
class RecentSearch extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'recent_search';

    protected $casts = [
        'id_user' => 'int',
        'id_search_user' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'phrase',
        'id_search_user',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\RecentSearchFactory
    {
        return \Database\Factories\RecentSearchFactory::new();
    }
}
