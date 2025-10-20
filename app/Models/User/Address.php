<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Finance\Invoice|null $invoice
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAddressType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereIdInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereZip($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\AddressFactory>
 */
class Address extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
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

    /**
     * @return BelongsTo<\App\Models\Finance\Invoice, $this>
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Finance\Invoice::class, 'id_invoice');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
