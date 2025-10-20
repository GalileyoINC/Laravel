<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class AuthItemChild
 *
 * @property string $parent
 * @property string $child
 * @property-read AuthItem $auth_item
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthItemChild newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthItemChild newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthItemChild query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthItemChild whereChild($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthItemChild whereParent($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\AuthItemChildFactory>
 */
class AuthItemChild extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'auth_item_child';

    public function auth_item(): BelongsTo
    {
        return $this->belongsTo(AuthItem::class, 'child');
    }
}
