<?php

declare(strict_types=1);

namespace App\Http\Requests\Communication\Web;

use Illuminate\Foundation\Http\FormRequest;

class SendSmsRequest extends FormRequest
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
            'body' => ['required', 'string', 'max:160'],
            'obj_type' => ['required', 'integer'],
            'obj_id' => ['nullable', 'string'],
            'state' => ['nullable', 'string', 'max:2'],
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
            'body.required' => 'The message body is required.',
            'body.max' => 'The message body may not be greater than 160 characters.',
            'obj_type.required' => 'The object type is required.',
            'obj_type.integer' => 'The object type must be an integer.',
            'state.max' => 'The state may not be greater than 2 characters.',
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
            'body' => 'message body',
            'obj_type' => 'object type',
            'obj_id' => 'object ID',
            'state' => 'state',
        ];
    }
}
