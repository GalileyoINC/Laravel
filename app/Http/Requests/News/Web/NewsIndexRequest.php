<?php

declare(strict_types=1);

namespace App\Http\Requests\News\Web;

use Illuminate\Foundation\Http\FormRequest;

class NewsIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
