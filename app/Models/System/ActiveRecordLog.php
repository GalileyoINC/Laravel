<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @property array|null $changes
 * @property Carbon $created_at
 */
class ActiveRecordLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'active_record_log';

    protected $casts = [
        'id_user' => 'int',
        'id_staff' => 'int',
        'action_type' => 'int',
        'changes' => 'json',
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

    // Relationships
    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    public function staff()
    {
        return $this->belongsTo(\App\Models\System\Staff::class, 'id_staff');
    }

    // Action type labels for filters and exports
    public static function getActionTypeList(): array
    {
        $defaults = [
            1 => 'Create',
            2 => 'Update',
            3 => 'Delete',
        ];

        try {
            $codes = static::query()->select('action_type')->distinct()->pluck('action_type')->filter(function ($v) {
                return $v !== null;
            })->all();

            $map = $defaults;
            foreach ($codes as $code) {
                if (!array_key_exists($code, $map)) {
                    $map[$code] = 'Action '.$code;
                }
            }
            ksort($map);
            return $map;
        } catch (\Throwable) {
            return $defaults;
        }
    }
}
