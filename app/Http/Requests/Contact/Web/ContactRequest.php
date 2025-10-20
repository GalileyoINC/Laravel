<?php

declare(strict_types=1);

namespace App\Http\Requests\Contact\Web;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'id_user' => ['nullable', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'status' => ['nullable', 'integer', 'in:1,2,3'],
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
            'id_user.integer' => 'The user ID must be an integer.',
            'id_user.exists' => 'The selected user does not exist.',
            'name.required' => 'The name is required.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'subject.max' => 'The subject may not be greater than 255 characters.',
            'body.required' => 'The body is required.',
            'status.in' => 'The selected status is invalid.',
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
            'id_user' => 'user',
            'name' => 'name',
            'email' => 'email',
            'subject' => 'subject',
            'body' => 'body',
            'status' => 'status',
        ];
    }
}
