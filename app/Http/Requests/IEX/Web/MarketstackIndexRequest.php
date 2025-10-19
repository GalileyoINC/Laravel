<?php

declare(strict_types=1);

namespace App\Http\Requests\IEX\Web;

use Illuminate\Foundation\Http\FormRequest;

class MarketstackIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:100'],
            'currency' => ['nullable', 'string', 'max:10'],
            'has_intraday' => ['nullable', 'integer', 'in:0,1'],
            'has_eod' => ['nullable', 'integer', 'in:0,1'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
