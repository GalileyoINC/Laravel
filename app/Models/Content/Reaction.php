<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Content;

use Database\Factories\ReactionFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Reaction
 *
 * @property int $id
 * @property string $emoji
 * @property Collection|SmsPool[] $sms_pools
 */
class Reaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'reaction';

    protected $fillable = [
        'emoji',
    ];

    public function sms_pools()
    {
        return $this->belongsToMany(App\Models\Communication\SmsPool::class, 'sms_pool_reaction', 'id_reaction', 'id_sms_pool')
            ->withPivot('id_user');
    }

    protected static function newFactory()
    {
        return ReactionFactory::new();
    }
}
