<?php

declare(strict_types=1);

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PaymentListRequest
 * Form request for payment list validation
 */
class PaymentListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'type' => ['sometimes', 'string', 'in:authorize,bitpay,apply_credit,pay_from_credit,discount,apple'],
            'is_success' => ['sometimes', 'boolean'],
        ];
    }
}
