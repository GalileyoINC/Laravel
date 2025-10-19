<?php

declare(strict_types=1);

namespace App\Models\Device;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PhoneNumber
 *
 * @property int $id
 * @property int|null $id_user
 * @property string|null $phone_number
 * @property bool $is_active
 * @property bool|null $is_valid
 * @property bool|null $is_primary
 * @property bool|null $is_send
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneNumber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneNumber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneNumber query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneNumber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneNumber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneNumber whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneNumber whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneNumber wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneNumber whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PhoneNumber extends Model
{
    use HasFactory;

    protected $table = 'phone_numbers';

    protected $casts = [
        'id_user' => 'int',
        'is_active' => 'bool',
        'is_valid' => 'bool',
        'is_primary' => 'bool',
        'is_send' => 'bool',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'id_user',
        'phone_number',
        'is_active',
        'is_valid',
        'is_primary',
        'is_send',
    ];

    /**
     * @return BelongsTo<\App\Models\User\User, static>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    /**
     * Get the phone number (alias for phone_number property)
     */
    public function getNumberAttribute(): ?string
    {
        return $this->phone_number;
    }

    /**
     * Get full type name for display
     */
    public function getFullTypeName(): string
    {
        $types = [];

        if ($this->is_primary) {
            $types[] = 'Primary';
        }

        if ($this->is_send) {
            $types[] = 'Send';
        }

        if ($this->is_valid) {
            $types[] = 'Valid';
        }

        return empty($types) ? 'Unknown' : implode(', ', $types);
    }
}
