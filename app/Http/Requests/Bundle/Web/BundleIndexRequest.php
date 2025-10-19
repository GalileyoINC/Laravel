<?php

declare(strict_types=1);

namespace App\Http\Requests\Bundle\Web;

use Illuminate\Foundation\Http\FormRequest;

class BundleIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['nullable', 'integer', 'min:1'],
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
