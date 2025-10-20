<?php

declare(strict_types=1);

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class ChatMessagesRequest extends FormRequest
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
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id_conversation' => ['required', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'offset' => ['nullable', 'integer', 'min:0'],
            'last_message_id' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'id_conversation.required' => 'Conversation ID is required',
            'id_conversation.integer' => 'Conversation ID must be a number',
            'id_conversation.min' => 'Conversation ID must be at least 1',
            'limit.integer' => 'Limit must be a number',
            'limit.min' => 'Limit must be at least 1',
            'limit.max' => 'Limit cannot exceed 100',
            'offset.integer' => 'Offset must be a number',
            'offset.min' => 'Offset must be at least 0',
            'last_message_id.integer' => 'Last message ID must be a number',
            'last_message_id.min' => 'Last message ID must be at least 1',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    /**
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return [
            'id_conversation' => 'conversation ID',
            'limit' => 'limit',
            'offset' => 'offset',
            'last_message_id' => 'last message ID',
        ];
    }
}
