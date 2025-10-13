<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Finance\CreditCard;
use App\Models\Finance\Service;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $casts = [
        'id_user' => 'int',
        'id_product' => 'int',
        'quantity' => 'int',
        'total_amount' => 'float',
        'is_paid' => 'bool',
        'product_details' => 'json',
        'id_credit_card' => 'int',
        'payment_details' => 'json',
    ];

    protected $fillable = [
        'id_user',
        'id_product',
        'quantity',
        'total_amount',
        'payment_method',
        'status',
        'is_paid',
        'notes',
        'product_details',
        'id_credit_card',
        'payment_reference',
        'payment_details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function product()
    {
        return $this->belongsTo(Service::class, 'id_product');
    }

    public function creditCard()
    {
        return $this->belongsTo(CreditCard::class, 'id_credit_card');
    }
}
