<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SettingsSafe
 *
 * @property string $name
 * @property string $value
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingsSafe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingsSafe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingsSafe query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingsSafe whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingsSafe whereValue($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\SettingsSafeFactory>
 */
class SettingsSafe extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'settings_safe';

    protected $primaryKey = 'name';

    protected $fillable = [
        'value',
    ];
}
