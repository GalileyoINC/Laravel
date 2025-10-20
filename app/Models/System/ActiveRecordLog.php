<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Throwable;

/**
 * Class ActiveRecordLog
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_staff
 * @property int|null $action_type
 * @property string|null $model
 * @property string|null $id_model
 * @property string|null $field
 * @property array<array-key, mixed>|null $changes
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read Staff|null $staff
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveRecordLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveRecordLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveRecordLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveRecordLog whereActionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveRecordLog whereChanges($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveRecordLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveRecordLog whereField($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveRecordLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveRecordLog whereIdModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveRecordLog whereIdStaff($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveRecordLog whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveRecordLog whereModel($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ActiveRecordLogFactory>
 */
class ActiveRecordLog extends Model
{
    use HasFactory; /** @phpstan-ignore-line */ /** @phpstan-ignore-line */

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'active_record_log';

    protected $casts = [
        'id_user' => 'int',
        'id_staff' => 'int',
        'action_type' => 'int',
        'changes' => 'json',
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'id_user',
        'id_staff',
        'action_type',
        'model',
        'id_model',
        'field',
        'changes',
    ];

    // Action type labels for filters and exports
    /**
     * @return array<int, string>
     */
    public static function getActionTypeList(): array
    {
        $defaults = [
            1 => 'Create',
            2 => 'Update',
            3 => 'Delete',
        ];

        try {
            $codes = static::query()->select('action_type')->distinct()->pluck('action_type')->filter(fn ($v) => $v !== null)->all();

            $map = $defaults;
            foreach ($codes as $code) {
                if (! array_key_exists($code, $map)) {
                    $map[$code] = 'Action '.$code;
                }
            }
            ksort($map);

            return $map;
        } catch (Throwable) {
            return $defaults;
        }
    }

    // Relationships
    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * @return BelongsTo<Staff, $this>
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'id_staff');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\ActiveRecordLogFactory
    {
        return \Database\Factories\ActiveRecordLogFactory::new();
    }
}
