<?php

declare(strict_types=1);

namespace App\Http\Requests\EmailPool;

use Illuminate\Foundation\Http\FormRequest;

class EmailPoolListRequest extends FormRequest
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
            'status' => 'sometimes|string|max:50',
            'to' => 'sometimes|email',
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
            'status.string' => 'Status must be a string',
            'status.max' => 'Status cannot exceed 50 characters',
            'to.email' => 'To must be a valid email address',
        ];
    }
}
