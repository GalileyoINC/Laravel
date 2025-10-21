<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class AuthItem
 *
 * @property string $name
 * @property int $type
 * @property string|null $description
 * @property string|null $rule_name
 * @property string|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, AuthAssignment> $auth_assignments
 * @property-read int|null $auth_assignments_count
 * @property-read Collection<int, AuthItemChild> $auth_item_children
 * @property-read int|null $auth_item_children_count
 * @property-read AuthRule|null $auth_rule
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthItem whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthItem whereRuleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthItem whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthItem whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\AuthItemFactory>
 */
class AuthItem extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $incrementing = false;

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'auth_item';

    protected $primaryKey = 'name';

    protected $casts = [
        'type' => 'int',
    ];

    protected $fillable = [
        'type',
        'description',
        'rule_name',
        'data',
    ];

    /**
     * @return BelongsTo<AuthRule, $this>
     */
    public function auth_rule(): BelongsTo
    {
        return $this->belongsTo(AuthRule::class, 'rule_name');
    }

    /**
     * @return HasMany<AuthAssignment, $this>
     */
    public function auth_assignments(): HasMany
    {
        return $this->hasMany(AuthAssignment::class, 'item_name');
    }

    /**
     * @return HasMany<AuthItemChild, $this>
     */
    public function auth_item_children(): HasMany
    {
        return $this->hasMany(AuthItemChild::class, 'child');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\AuthItemFactory
    {
        return \Database\Factories\AuthItemFactory::new();
    }
}
