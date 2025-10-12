<?php

declare(strict_types=1);

namespace App\Modules\Finance\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->id_user,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'email' => $this->user->email,
            ]),
            'card_number' => $this->card_number,
            'cardholder_name' => $this->cardholder_name,
            'expiry_month' => $this->expiry_month,
            'expiry_year' => $this->expiry_year,
            'is_active' => $this->is_active,
            'is_preferred' => $this->is_preferred,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
