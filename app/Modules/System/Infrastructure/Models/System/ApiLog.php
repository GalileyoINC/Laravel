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
 * Class ApiLog
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property Carbon $created_at
 */
class ApiLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'api_log';

    protected $fillable = [
        'key',
        'value',
    ];
}
