<?php

declare(strict_types=1);

namespace App\Http\Requests\Product\Web;

use Illuminate\Foundation\Http\FormRequest;

class ProductDeviceIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
            'price_min' => ['nullable', 'numeric'],
            'price_max' => ['nullable', 'numeric'],
        ];
    }
}
