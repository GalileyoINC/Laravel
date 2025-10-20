<?php

declare(strict_types=1);

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class ReportCsvRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'csv' => 'sometimes|boolean',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:100',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'name.string' => 'Name must be a string',
            'name.max' => 'Name cannot exceed 255 characters',
            'csv.boolean' => 'CSV must be a boolean',
            'page.integer' => 'Page must be an integer',
            'page.min' => 'Page must be at least 1',
            'limit.integer' => 'Limit must be an integer',
            'limit.min' => 'Limit must be at least 1',
            'limit.max' => 'Limit cannot exceed 100',
        ];
    }
}
