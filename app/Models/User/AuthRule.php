<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AuthRule
 *
 * @property string $name
 * @property string|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, AuthItem> $auth_items
 * @property-read int|null $auth_items_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthRule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthRule whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthRule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthRule whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class AuthRule extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'auth_rule';

    protected $primaryKey = 'name';

    protected $fillable = [
        'data',
    ];

    public function auth_items()
    {
        return $this->hasMany(AuthItem::class, 'rule_name');
    }
}
