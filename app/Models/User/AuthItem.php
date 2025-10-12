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
 * Class AuthItem
 *
 * @property string $name
 * @property int $type
 * @property string|null $description
 * @property string|null $rule_name
 * @property string|null $data
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property AuthRule|null $auth_rule
 * @property Collection|AuthAssignment[] $auth_assignments
 * @property Collection|AuthItemChild[] $auth_item_children
 */
class AuthItem extends Model
{
    use HasFactory;

    public $incrementing = false;

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

    public function auth_rule()
    {
        return $this->belongsTo(AuthRule::class, 'rule_name');
    }

    public function auth_assignments()
    {
        return $this->hasMany(AuthAssignment::class, 'item_name');
    }

    public function auth_item_children()
    {
        return $this->hasMany(AuthItemChild::class, 'child');
    }
}
