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
 * Class AdminMessageLog
 *
 * @property int $id
 * @property int $id_staff
 * @property int|null $obj_type
 * @property string|null $obj_id
 * @property string|null $body
 * @property Carbon|null $created_at
 */
class AdminMessageLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'admin_message_log';

    // Object type constants (used for filtering/log view links)
    public const TO_ALL = 1;
    public const TO_STATE = 2;

    protected $casts = [
        'id_staff' => 'int',
        'obj_type' => 'int',
    ];

    protected $fillable = [
        'id_staff',
        'obj_type',
        'obj_id',
        'body',
    ];
}
