<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Contact
 *
 * @property int $id
 * @property int|null $id_user
 * @property string $name
 * @property string $email
 * @property string|null $subject
 * @property string $body
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property int $status
 * @property User|null $user
 */
class Contact extends Model
{
    use HasFactory;

    protected $table = 'contact';

    protected $casts = [
        'id_user' => 'int',
        'status' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'name',
        'email',
        'subject',
        'body',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(App\Models\User\User::class, 'id_user');
    }
}
