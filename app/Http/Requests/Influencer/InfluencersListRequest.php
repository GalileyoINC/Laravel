<?php

declare(strict_types=1);

namespace App\Http\Requests\Influencer;

use Illuminate\Foundation\Http\FormRequest;

class InfluencersListRequest extends FormRequest
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
            'limit' => 'nullable|integer|min:1|max:100',
            'offset' => 'nullable|integer|min:0',
            'search' => 'nullable|string|max:255',
            'verified_only' => 'nullable|boolean',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'limit.max' => 'Limit cannot exceed 100',
            'limit.min' => 'Limit must be at least 1',
            'offset.min' => 'Offset must be at least 0',
            'search.max' => 'Search term cannot exceed 255 characters',
        ];
    }
}
