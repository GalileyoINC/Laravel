<?php

declare(strict_types=1);

namespace App\Http\Requests\IEX\Web;

use Illuminate\Foundation\Http\FormRequest;

class MarketstackStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'country' => 'required|string|max:100',
            'currency' => 'required|string|max:10',
            'has_intraday' => 'boolean',
            'has_eod' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
