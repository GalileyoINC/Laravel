<?php

declare(strict_types=1);

namespace App\Http\Requests\Promocode\Web;

use Illuminate\Foundation\Http\FormRequest;

class PromocodeIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:discount,trial,influencer,test'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
            'active_from_from' => ['nullable', 'date'],
            'active_from_to' => ['nullable', 'date'],
            'active_to_from' => ['nullable', 'date'],
            'active_to_to' => ['nullable', 'date'],
        ];
    }
}
