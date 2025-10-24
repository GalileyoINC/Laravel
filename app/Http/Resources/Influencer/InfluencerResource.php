<?php

declare(strict_types=1);

namespace App\Http\Resources\Influencer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InfluencerResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'is_influencer' => $this->is_influencer,
            'influencer_verified_at' => $this->influencer_verified_at,
            'created_at' => $this->created_at,
            'about' => $this->about,
            'avatar' => $this->avatar,
            'header_image' => $this->header_image,
        ];
    }
}
