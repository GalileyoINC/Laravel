<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Analytics;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Logger
 *
 * @property int $id
 * @property string|null $employee_login
 * @property string|null $employee_first_name
 * @property string|null $employee_last_name
 * @property int|null $access_level
 * @property Carbon|null $created_at
 * @property string|null $category
 * @property string|null $error_type
 * @property string|null $source
 * @property string|null $content
 * @property string|null $module
 * @property string|null $controller
 * @property string|null $action
 * @property string|null $ip
 * @property array|null $primary_json
 * @property string|null $user_agent
 * @property array|null $changed_fields
 */
class Logger extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'logger';

    protected $casts = [
        'access_level' => 'int',
        'primary_json' => 'json',
        'changed_fields' => 'json',
    ];

    protected $fillable = [
        'employee_login',
        'employee_first_name',
        'employee_last_name',
        'access_level',
        'category',
        'error_type',
        'source',
        'content',
        'module',
        'controller',
        'action',
        'ip',
        'primary_json',
        'user_agent',
        'changed_fields',
    ];
}
