<?php

declare(strict_types=1);

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class ChatListRequest extends FormRequest
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
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'offset' => ['nullable', 'integer', 'min:0'],
            'type' => ['nullable', 'string', 'in:private,group,all'],
            'status' => ['nullable', 'string', 'in:active,archived,all'],
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
            'limit.integer' => 'Limit must be a number',
            'limit.min' => 'Limit must be at least 1',
            'limit.max' => 'Limit cannot exceed 100',
            'offset.integer' => 'Offset must be a number',
            'offset.min' => 'Offset must be at least 0',
            'type.in' => 'Type must be one of: private, group, all',
            'status.in' => 'Status must be one of: active, archived, all',
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
            'limit' => 'limit',
            'offset' => 'offset',
            'type' => 'chat type',
            'status' => 'chat status',
        ];
    }
}
