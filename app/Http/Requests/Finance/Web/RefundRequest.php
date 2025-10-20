<?php

declare(strict_types=1);

namespace App\Http\Requests\Finance\Web;

use Illuminate\Foundation\Http\FormRequest;

class RefundRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
     */
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors
     */
    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'amount.required' => 'The refund amount is required.',
            'amount.numeric' => 'The refund amount must be a number.',
            'amount.min' => 'The refund amount must be at least 0.01.',
            'reason.max' => 'The reason may not be greater than 255 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors
     */
    /**
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return [
            'amount' => 'refund amount',
            'reason' => 'reason',
        ];
    }
}
