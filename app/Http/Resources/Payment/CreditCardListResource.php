<?php

declare(strict_types=1);

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Payment\CreditCardResource;

/**
 * CreditCardListResource
 * Resource for credit card list data transformation
 */
class CreditCardListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'count' => $this->resource['count'],
            'page' => $this->resource['page'],
            'page_size' => $this->resource['page_size'],
            'list' => CreditCardResource::collection($this->resource['list']),
        ];
    }
}
