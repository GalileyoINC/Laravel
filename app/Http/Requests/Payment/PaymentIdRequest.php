<?php

declare(strict_types=1);

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PaymentIdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:credit_cards,id'],
        ];
    }
}
