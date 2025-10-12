<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MemberRequest
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_user_from
 * @property int|null $type
 * @property string|null $text
 * @property array|null $params
 * @property int $is_active
 * @property User|null $user
 */
class MemberRequest extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'member_request';

    protected $casts = [
        'id_user' => 'int',
        'id_user_from' => 'int',
        'type' => 'int',
        'params' => 'json',
        'is_active' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_user_from',
        'type',
        'text',
        'params',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(App\Models\User\User::class, 'id_user_from');
    }
}
