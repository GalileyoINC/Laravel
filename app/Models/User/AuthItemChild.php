<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AuthItemChild
 *
 * @property string $parent
 * @property string $child
 * @property AuthItem $auth_item
 */
class AuthItemChild extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'auth_item_child';

    public function auth_item()
    {
        return $this->belongsTo(AuthItem::class, 'child');
    }
}
