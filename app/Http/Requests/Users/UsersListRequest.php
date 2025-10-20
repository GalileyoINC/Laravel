<?php

declare(strict_types=1);

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UsersListRequest extends FormRequest
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
            'page' => ['nullable', 'integer', 'min:1'],
            'page_size' => ['nullable', 'integer', 'min:1', 'max:100'],
            'search' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'integer', 'min:1', 'max:3'],
            'valid_email_only' => ['nullable', 'boolean'],
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
            'page.integer' => 'Page must be an integer',
            'page.min' => 'Page must be at least 1',
            'page_size.integer' => 'Page size must be an integer',
            'page_size.min' => 'Page size must be at least 1',
            'page_size.max' => 'Page size cannot exceed 100',
            'search.max' => 'Search term cannot exceed 255 characters',
            'role.integer' => 'Role must be an integer',
            'role.min' => 'Role must be at least 1',
            'role.max' => 'Role cannot exceed 3',
            'valid_email_only.boolean' => 'Valid email only must be a boolean',
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
            'page' => 'page number',
            'page_size' => 'page size',
            'search' => 'search term',
            'role' => 'user role',
            'valid_email_only' => 'valid email only filter',
        ];
    }
}
