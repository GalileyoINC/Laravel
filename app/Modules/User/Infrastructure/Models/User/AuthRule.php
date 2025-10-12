<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Modules\User\Infrastructure\Models\User;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AuthRule
 *
 * @property string $name
 * @property string|null $data
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property Collection|AuthItem[] $auth_items
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
