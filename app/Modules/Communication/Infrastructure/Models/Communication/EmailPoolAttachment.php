<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailPoolAttachment
 *
 * @property int $id
 * @property int $id_email_pool
 * @property string $body
 * @property string $file_name
 * @property string|null $content_type
 * @property EmailPool $email_pool
 */
class EmailPoolAttachment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'email_pool_attachment';

    protected $casts = [
        'id_email_pool' => 'int',
    ];

    protected $fillable = [
        'id_email_pool',
        'body',
        'file_name',
        'content_type',
    ];

    public function email_pool()
    {
        return $this->belongsTo(EmailPool::class, 'id_email_pool');
    }
}
