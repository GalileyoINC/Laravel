<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AuthAssignment
 *
 * @property string $item_name
 * @property string $user_id
 * @property int|null $created_at
 * @property AuthItem $auth_item
 */
class AuthAssignment extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'auth_assignment';

    public function auth_item()
    {
        return $this->belongsTo(AuthItem::class, 'item_name');
    }
}
