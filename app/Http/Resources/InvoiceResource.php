<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Finance\Invoice
 */
class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getAttribute('id'),
            'user_id' => $this->getAttribute('user_id'),
            'total' => $this->getAttribute('total'),
            'paid_status' => $this->getAttribute('paid_status'),
            'created_at' => $this->getAttribute('created_at'),
            'updated_at' => $this->getAttribute('updated_at'),
            'user' => $this->whenLoaded('user'),
            'invoice_lines' => $this->whenLoaded('invoiceLines'),
            'money_transactions' => $this->whenLoaded('moneyTransactions'),
        ];
    }
}
