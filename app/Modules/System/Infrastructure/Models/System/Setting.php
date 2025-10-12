<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 *
 * @property string $name
 * @property string $prod
 * @property string $dev
 */
class Setting extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'settings';

    protected $primaryKey = 'name';

    protected $fillable = [
        'prod',
        'dev',
    ];
}
