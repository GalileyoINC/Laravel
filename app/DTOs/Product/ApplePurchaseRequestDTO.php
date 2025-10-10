<?php

namespace App\DTOs\Product;

use Illuminate\Http\Request;

class ApplePurchaseRequestDTO
{
    public function __construct(
        public readonly string $receiptData,
        public readonly string $productId,
        public readonly ?string $transactionId = null,
        public readonly ?array $additionalData = []
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            receiptData: $data['receipt_data'],
            productId: $data['product_id'],
            transactionId: $data['transaction_id'] ?? null,
            additionalData: $data['additional_data'] ?? []
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            receiptData: $request->input('receipt_data'),
            productId: $request->input('product_id'),
            transactionId: $request->input('transaction_id'),
            additionalData: $request->input('additional_data', [])
        );
    }

    public function toArray(): array
    {
        return [
            'receipt_data' => $this->receiptData,
            'product_id' => $this->productId,
            'transaction_id' => $this->transactionId,
            'additional_data' => $this->additionalData
        ];
    }

    public function validate(): bool
    {
        return !empty($this->receiptData) && !empty($this->productId);
    }
}
