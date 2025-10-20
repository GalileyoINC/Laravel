<?php

declare(strict_types=1);

namespace App\Http\Requests\Bundle;

use Illuminate\Foundation\Http\FormRequest;

class BundleListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:100',
            'search' => 'sometimes|string|max:255',
            'status' => 'sometimes|integer|in:0,1',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'page.integer' => 'Page must be an integer',
            'page.min' => 'Page must be at least 1',
            'limit.integer' => 'Limit must be an integer',
            'limit.min' => 'Limit must be at least 1',
            'limit.max' => 'Limit cannot exceed 100',
            'search.string' => 'Search must be a string',
            'search.max' => 'Search cannot exceed 255 characters',
            'status.integer' => 'Status must be an integer',
            'status.in' => 'Status must be 0 or 1',
        ];
    }
}
