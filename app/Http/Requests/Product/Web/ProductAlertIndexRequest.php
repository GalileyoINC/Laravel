<?php

declare(strict_types=1);

namespace App\Http\Requests\Product\Web;

use Illuminate\Foundation\Http\FormRequest;

class ProductAlertIndexRequest extends FormRequest
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
            'search' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
