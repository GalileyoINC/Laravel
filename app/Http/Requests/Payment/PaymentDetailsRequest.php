<?php

declare(strict_types=1);

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PaymentDetailsRequest
 * Form request for payment details validation
 */
class PaymentDetailsRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'card_number' => ['required', 'string', 'min:13', 'max:19'],
            'security_code' => ['required', 'string', 'min:3', 'max:4'],
            'expiration_year' => ['required', 'string', 'size:4'],
            'expiration_month' => ['required', 'string', 'min:1', 'max:2'],
            'zip' => ['required', 'string', 'max:10'],
            'is_agree_to_receive' => ['sometimes', 'boolean'],
            'id' => ['sometimes', 'integer', 'exists:credit_cards,id'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'phone.required' => 'Phone number is required',
            'card_number.required' => 'Card number is required',
            'security_code.required' => 'Security code is required',
            'expiration_year.required' => 'Expiration year is required',
            'expiration_month.required' => 'Expiration month is required',
            'zip.required' => 'ZIP code is required',
        ];
    }
}
