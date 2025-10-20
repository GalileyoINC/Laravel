<?php

declare(strict_types=1);

namespace App\Http\Requests\Content\Web;

use Illuminate\Foundation\Http\FormRequest;

class PageIndexRequest extends FormRequest
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
            'status' => ['nullable', 'integer', 'in:0,1'],
            'createTimeRange' => ['nullable', 'string', 'max:255'],
        ];
    }
}
