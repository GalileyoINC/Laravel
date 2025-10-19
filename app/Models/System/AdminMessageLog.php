<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminMessageLog
 *
 * @property int $id
 * @property int $id_staff
 * @property int|null $obj_type
 * @property string|null $obj_id
 * @property string|null $body
 * @property \Illuminate\Support\Carbon $created_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMessageLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMessageLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMessageLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMessageLog whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMessageLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMessageLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMessageLog whereIdStaff($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMessageLog whereObjId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMessageLog whereObjType($value)
 *
 * @mixin \Eloquent
 */
class AdminMessageLog extends Model
{
    use HasFactory;

    // Object type constants (used for filtering/log view links)
    public const TO_ALL = 1;

    public const TO_STATE = 2;

    public $timestamps = false;

    protected $table = 'admin_message_log';

    protected $casts = [
        'id_staff' => 'int',
        'obj_type' => 'int',
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'id_staff',
        'obj_type',
        'obj_id',
        'body',
    ];
}
