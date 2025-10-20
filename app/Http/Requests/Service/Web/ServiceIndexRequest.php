<?php

declare(strict_types=1);

namespace App\Http\Requests\Service\Web;

use Illuminate\Foundation\Http\FormRequest;

class ServiceIndexRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:255'],
            'price_from' => ['nullable', 'numeric'],
            'price_to' => ['nullable', 'numeric'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
            'type' => ['nullable', 'string', 'max:50'],
        ];
    }
}
