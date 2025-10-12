<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Modules\User\Infrastructure\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RecentSearch
 *
 * @property int $id
 * @property int $id_user
 * @property string|null $phrase
 * @property int|null $id_search_user
 */
class RecentSearch extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'recent_search';

    protected $casts = [
        'id_user' => 'int',
        'id_search_user' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'phrase',
        'id_search_user',
    ];
}
