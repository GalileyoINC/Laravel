<?php

declare(strict_types=1);

namespace App\Modules\Finance\Infrastructure\Http\Requests\ContractLine;

use Illuminate\Foundation\Http\FormRequest;

class ContractLineListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:100',
            'search' => 'sometimes|string|max:255',
            'user_id' => 'sometimes|integer|exists:users,id',
            'service_id' => 'sometimes|integer|exists:services,id',
            'status' => 'sometimes|string|in:unpaid,paid,all',
        ];
    }

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
            'user_id.integer' => 'User ID must be an integer',
            'user_id.exists' => 'User must exist',
            'service_id.integer' => 'Service ID must be an integer',
            'service_id.exists' => 'Service must exist',
            'status.string' => 'Status must be a string',
            'status.in' => 'Status must be unpaid, paid, or all',
        ];
    }
}
