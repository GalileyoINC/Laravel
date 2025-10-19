<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Affiliate
 *
 * @property int $id
 * @property int $id_user_parent
 * @property int $id_user_child
 * @property bool|null $is_active
 * @property array|null $params
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property User $user
 */
class Affiliate extends Model
{
    use HasFactory;

    protected $table = 'affiliate';

    protected $casts = [
        'id_user_parent' => 'int',
        'id_user_child' => 'int',
        'is_active' => 'bool',
        'params' => 'json',
    ];

    protected $fillable = [
        'id_user_parent',
        'id_user_child',
        'is_active',
        'params',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user_parent');
    }
}
