<?php

declare(strict_types=1);

namespace App\Http\Requests\CreditCard;

use Illuminate\Foundation\Http\FormRequest;

class CreditCardListRequest extends FormRequest
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
            'is_active' => 'sometimes|boolean',
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
            'is_active.boolean' => 'Is active must be a boolean',
        ];
    }
}
