<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Database\Factories\SettingFactory as RootSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 *
 * @property string $name
 * @property string $prod
 * @property string $dev
 *
 * @method static \Database\Factories\SystemSettingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereDev($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereProd($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\SettingFactory>
 */
class Setting extends Model
{
    use HasFactory; /** @phpstan-ignore-line */

    /** @phpstan-ignore-line */
    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'settings';

    protected $primaryKey = 'name';

    protected $fillable = [
        'prod',
        'dev',
    ];

    /**
     * Create a new factory instance for the model.
     */
    /**
     * @return SystemSettingFactory
     */
    protected static function newFactory(): RootSettingFactory
    {
        return RootSettingFactory::new();
    }
}
