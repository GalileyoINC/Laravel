<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserService
 *
 * @property int|null $id_user
 * @property int|null $id_service
 * @property Service|null $service
 * @property User|null $user
 */
class UserService extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'user_service';

    protected $casts = [
        'id_user' => 'int',
        'id_service' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_service',
    ];

    public function service()
    {
        return $this->belongsTo(\App\Models\Finance\Service::class, 'id_service');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }
}
