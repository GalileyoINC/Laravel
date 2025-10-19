<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Finance\CreditCard
 */
class CreditCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->id_user,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => data_get($this, 'user.id'),
                'first_name' => data_get($this, 'user.first_name'),
                'last_name' => data_get($this, 'user.last_name'),
                'email' => data_get($this, 'user.email'),
            ]),
            'card_number' => $this->getAttribute('num'),
            'cardholder_name' => trim((string)($this->getAttribute('first_name') ?? '') . ' ' . (string)($this->getAttribute('last_name') ?? '')),
            'expiry_month' => $this->getAttribute('expiration_month'),
            'expiry_year' => $this->getAttribute('expiration_year'),
            'is_active' => $this->getAttribute('is_active'),
            'is_preferred' => $this->getAttribute('is_preferred'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
