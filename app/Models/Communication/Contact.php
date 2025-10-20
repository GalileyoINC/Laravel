<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Database\Factories\CommunicationContactFactory;
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
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $status
 * @property-read \App\Models\User\User|null $user
 *
 * @method static \Database\Factories\CommunicationContactFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\CommunicationContactFactory>
 */
class Contact extends Model
{
    use HasFactory;

    /**
     * Status constants
     */
    public const STATUS_PENDING = 0;

    public const STATUS_REPLIED = 1;

    public const STATUS_DELETED = 2;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return CommunicationContactFactory::new();
    }
}
