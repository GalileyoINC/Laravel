<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CommentListRequest extends FormRequest
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
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'offset' => ['nullable', 'integer', 'min:0'],
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
            'limit.integer' => 'Limit must be a number',
            'limit.min' => 'Limit must be at least 1',
            'limit.max' => 'Limit cannot exceed 100',
            'offset.integer' => 'Offset must be a number',
            'offset.min' => 'Offset must be at least 0',
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
            'limit' => 'limit',
            'offset' => 'offset',
            'id_parent' => 'parent comment ID',
        ];
    }
}