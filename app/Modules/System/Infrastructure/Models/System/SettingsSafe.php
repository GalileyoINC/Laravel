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
