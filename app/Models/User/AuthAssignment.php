<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
class AuthAssignment extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'auth_assignment';

    public function auth_item()
    {
        return $this->belongsTo(AuthItem::class, 'item_name');
    }
}
