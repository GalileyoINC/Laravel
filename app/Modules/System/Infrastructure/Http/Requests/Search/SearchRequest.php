<?php

declare(strict_types=1);

namespace App\Modules\System\Infrastructure\Http\Requests\Search;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'phrase' => ['required', 'string', 'min:3', 'max:255'],
            'page' => ['nullable', 'integer', 'min:1'],
            'page_size' => ['nullable', 'integer', 'min:1', 'max:100'],
            'type' => ['nullable', 'string', 'max:50'],
            'filters' => ['nullable', 'array'],
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
            'phrase.required' => 'Search phrase is required',
            'phrase.min' => 'Search phrase must be at least 3 characters',
            'phrase.max' => 'Search phrase cannot exceed 255 characters',
            'page.integer' => 'Page must be an integer',
            'page.min' => 'Page must be at least 1',
            'page_size.integer' => 'Page size must be an integer',
            'page_size.min' => 'Page size must be at least 1',
            'page_size.max' => 'Page size cannot exceed 100',
            'type.max' => 'Type cannot exceed 50 characters',
            'filters.array' => 'Filters must be an array',
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
            'phrase' => 'search phrase',
            'page' => 'page number',
            'page_size' => 'page size',
            'type' => 'content type',
            'filters' => 'search filters',
        ];
    }
}
