<?php

declare(strict_types=1);

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * CreditCardResource
 * Resource for credit card data transformation
 */
class CreditCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'num' => $this->num, // Already masked
            'type' => $this->type,
            'expiration_year' => $this->expiration_year,
            'expiration_month' => $this->expiration_month,
            'is_preferred' => $this->is_preferred,
            'created_at' => $this->created_at,
            'phone' => $this->phone,
            'zip' => $this->zip,
            'is_agree_to_receive' => $this->is_agree_to_receive,
            'masked_number' => $this->masked_number,
            'formatted_expiration' => $this->formatted_expiration,
            'is_expired' => $this->isExpired(),
        ];
    }
}
