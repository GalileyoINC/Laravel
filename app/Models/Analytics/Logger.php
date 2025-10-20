<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Analytics;

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
 * @property string|null $created_at
 * @property string|null $category
 * @property string|null $error_type Error/Warning/Info
 * @property string|null $source
 * @property string|null $content
 * @property string|null $module
 * @property string|null $controller
 * @property string|null $action
 * @property string|null $ip
 * @property array<array-key, mixed>|null $primary_json
 * @property string|null $user_agent
 * @property array<array-key, mixed>|null $changed_fields
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereAccessLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereChangedFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereController($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereEmployeeFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereEmployeeLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereEmployeeLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereErrorType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger wherePrimaryJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logger whereUserAgent($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\AnalyticsLoggerFactory>
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
