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
 * @property int|null $type
 * @property bool|null $is_valid
 * @property bool|null $is_primary
 * @property bool|null $is_send
 * @property string|null $twilio_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User\User|null $user
 * @property-read string|null $number
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
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\PhoneNumberFactory>
 */
class PhoneNumber extends Model
{
    use HasFactory; /** @phpstan-ignore-line */ /** @phpstan-ignore-line */ /** @phpstan-ignore-line */

    // Legacy type constants kept for BC with reports/controllers
    public const TYPE_NONE = 0;

    public const TYPE_SATELLITE = 1;

    public const TYPE_BIVY = 2;

    public const TYPE_PIVOTEL = 3;

    public const TYPE_MOBILE = 4;

    public ?int $id_user = null;

    public ?string $phone_number = null;

    public bool $is_active = false;

    public ?int $type = null;

    public ?bool $is_valid = null;

    public ?bool $is_primary = null;

    public ?bool $is_send = null;

    public ?string $twilio_type = null;

    public ?int $id_provider = null;

    protected $table = 'phone_number';

    protected $casts = [
        'id_user' => 'int',
        'type' => 'int',
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
        'type',
        'is_active',
        'is_valid',
        'is_primary',
        'is_send',
    ];

    /**
     * @return BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    /**
     * @return BelongsTo<\App\Models\Finance\Provider, $this>
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Finance\Provider::class, 'id_provider');
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

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\PhoneNumberFactory
    {
        return \Database\Factories\PhoneNumberFactory::new();
    }
}
