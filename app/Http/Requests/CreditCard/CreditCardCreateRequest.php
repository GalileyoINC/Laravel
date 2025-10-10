<?php

namespace App\Http\Requests\CreditCard;

use Illuminate\Foundation\Http\FormRequest;

class CreditCardCreateRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'num' => ['required', 'string', 'regex:/^[0-9]{13,19}$/'],
            'cvv' => ['required', 'string', 'regex:/^[0-9]{3,4}$/'],
            'type' => ['required', 'string', 'in:Visa,MasterCard,American Express,Discover'],
            'expiration_year' => ['required', 'integer', 'min:' . date('Y'), 'max:' . (date('Y') + 10)],
            'expiration_month' => ['required', 'integer', 'min:1', 'max:12'],
            'is_preferred' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required',
            'first_name.string' => 'First name must be text',
            'first_name.max' => 'First name cannot exceed 255 characters',
            'last_name.required' => 'Last name is required',
            'last_name.string' => 'Last name must be text',
            'last_name.max' => 'Last name cannot exceed 255 characters',
            'num.required' => 'Card number is required',
            'num.string' => 'Card number must be text',
            'num.regex' => 'Card number must be 13-19 digits',
            'cvv.required' => 'CVV is required',
            'cvv.string' => 'CVV must be text',
            'cvv.regex' => 'CVV must be 3-4 digits',
            'type.required' => 'Card type is required',
            'type.in' => 'Card type must be one of: Visa, MasterCard, American Express, Discover',
            'expiration_year.required' => 'Expiration year is required',
            'expiration_year.integer' => 'Expiration year must be a number',
            'expiration_year.min' => 'Expiration year cannot be in the past',
            'expiration_year.max' => 'Expiration year cannot be more than 10 years in the future',
            'expiration_month.required' => 'Expiration month is required',
            'expiration_month.integer' => 'Expiration month must be a number',
            'expiration_month.min' => 'Expiration month must be between 1 and 12',
            'expiration_month.max' => 'Expiration month must be between 1 and 12',
            'is_preferred.boolean' => 'Preferred status must be true or false',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'num' => 'card number',
            'cvv' => 'CVV',
            'type' => 'card type',
            'expiration_year' => 'expiration year',
            'expiration_month' => 'expiration month',
            'is_preferred' => 'preferred status',
        ];
    }
}