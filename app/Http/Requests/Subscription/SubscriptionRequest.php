<?php

declare(strict_types=1);

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
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
            'id' => ['required', 'integer', 'min:1'],
            'checked' => ['required', 'boolean'],
            'zip' => ['nullable', 'string', 'max:20'],
            'sub_type' => ['nullable', 'string', 'in:regular,premium,vip'],
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
            'id.required' => 'Subscription ID is required',
            'id.integer' => 'Subscription ID must be a number',
            'id.min' => 'Subscription ID must be at least 1',
            'checked.required' => 'Checked status is required',
            'checked.boolean' => 'Checked status must be true or false',
            'zip.string' => 'ZIP code must be text',
            'zip.max' => 'ZIP code cannot exceed 20 characters',
            'sub_type.string' => 'Subscription type must be text',
            'sub_type.in' => 'Subscription type must be one of: regular, premium, vip',
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
            'id' => 'subscription ID',
            'checked' => 'checked status',
            'zip' => 'ZIP code',
            'sub_type' => 'subscription type',
        ];
    }
}
