<?php

declare(strict_types=1);

namespace App\Http\Requests\Bookmark;

use Illuminate\Foundation\Http\FormRequest;

class BookmarkListRequest extends FormRequest
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
            'type' => ['nullable', 'string', 'max:50'],
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
            'type.max' => 'Type cannot exceed 50 characters',
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
            'type' => 'content type',
        ];
    }
}
