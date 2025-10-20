<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractLineResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->id_user,
            'service_id' => $this->id_service,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'email' => $this->user->email,
            ]),
            'service' => $this->whenLoaded('service', fn () => [
                'id' => $this->service->id,
                'name' => $this->service->name,
                'price' => $this->service->price,
            ]),
            'amount' => $this->amount,
            'status' => $this->status,
            'begin_at' => $this->begin_at,
            'end_at' => $this->end_at,
            'paid_at' => $this->paid_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
