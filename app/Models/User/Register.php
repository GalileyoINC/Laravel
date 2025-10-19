<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Register
 *
 * @property int $id
 * @property string $email
 * @property string|null $first_name
 * @property string|null $last_name
 * @property int|null $is_unfinished_signup
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Register newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Register newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Register query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Register whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Register whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Register whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Register whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Register whereIsUnfinishedSignup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Register whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Register whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Register extends Model
{
    use HasFactory;

    protected $table = 'register';

    protected $casts = [
        'is_unfinished_signup' => 'int',
    ];

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'is_unfinished_signup',
    ];
}
