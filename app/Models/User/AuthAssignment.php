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
 * Class AuthAssignment
 *
 * @property string $item_name
 * @property string $user_id
 * @property int|null $created_at
 * @property-read AuthItem $auth_item
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthAssignment whereItemName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthAssignment whereUserId($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\AuthAssignmentFactory>
 */
class AuthAssignment extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'auth_assignment';

    /**
     * @return BelongsTo<AuthItem, $this>
     */
    public function auth_item(): BelongsTo
    {
        return $this->belongsTo(AuthItem::class, 'item_name');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\AuthAssignmentFactory
    {
        return \Database\Factories\AuthAssignmentFactory::new();
    }
}
