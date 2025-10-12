<?php

declare(strict_types=1);

namespace App\Modules\Content\Infrastructure\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CommentCreateRequest extends FormRequest
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
            'id_sms_pool' => ['required', 'integer', 'min:1'],
            'message' => ['required', 'string', 'min:1', 'max:1000'],
            'id_parent' => ['nullable', 'integer', 'min:1'],
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
            'id_sms_pool.required' => 'SMS Pool ID is required',
            'id_sms_pool.integer' => 'SMS Pool ID must be a number',
            'id_sms_pool.min' => 'SMS Pool ID must be at least 1',
            'message.required' => 'Comment message is required',
            'message.string' => 'Comment message must be text',
            'message.min' => 'Comment message cannot be empty',
            'message.max' => 'Comment message cannot exceed 1000 characters',
            'id_parent.integer' => 'Parent comment ID must be a number',
            'id_parent.min' => 'Parent comment ID must be at least 1',
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
            'id_sms_pool' => 'SMS Pool ID',
            'message' => 'comment message',
            'id_parent' => 'parent comment ID',
        ];
    }
}
