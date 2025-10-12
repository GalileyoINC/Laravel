<?php

declare(strict_types=1);

namespace App\Modules\Content\Infrastructure\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;

class NewsListRequest extends FormRequest
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
            'category' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'integer', 'in:0,1,2'],
            'search' => ['nullable', 'string', 'max:255'],
            'sort_by' => ['nullable', 'string', 'in:created_at,updated_at,name,priority'],
            'sort_order' => ['nullable', 'string', 'in:asc,desc'],
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
            'category.string' => 'Category must be text',
            'category.max' => 'Category cannot exceed 100 characters',
            'status.integer' => 'Status must be a number',
            'status.in' => 'Status must be 0 (draft), 1 (published), or 2 (archived)',
            'search.string' => 'Search term must be text',
            'search.max' => 'Search term cannot exceed 255 characters',
            'sort_by.string' => 'Sort field must be text',
            'sort_by.in' => 'Sort field must be one of: created_at, updated_at, name, priority',
            'sort_order.string' => 'Sort order must be text',
            'sort_order.in' => 'Sort order must be asc or desc',
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
            'category' => 'category',
            'status' => 'status',
            'search' => 'search term',
            'sort_by' => 'sort field',
            'sort_order' => 'sort order',
        ];
    }
}
