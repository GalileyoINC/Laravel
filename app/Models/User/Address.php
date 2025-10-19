<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_invoice
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $company
 * @property string|null $email
 * @property string|null $phone_number
 * @property string|null $country
 * @property string|null $state
 * @property string|null $zip
 * @property string|null $city
 * @property string|null $address1
 * @property string|null $address2
 * @property int|null $address_type
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property Invoice|null $invoice
 * @property User|null $user
 */
class Address extends Model
{
    use HasFactory;

    protected $table = 'address';

    protected $casts = [
        'id_user' => 'int',
        'id_invoice' => 'int',
        'address_type' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_invoice',
        'first_name',
        'last_name',
        'company',
        'email',
        'phone_number',
        'country',
        'state',
        'zip',
        'city',
        'address1',
        'address2',
        'address_type',
    ];

    public function invoice()
    {
        return $this->belongsTo(\App\Models\Finance\Invoice::class, 'id_invoice');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
