<?php

declare(strict_types=1);

namespace App\Http\Requests\Report\Web;

use Illuminate\Foundation\Http\FormRequest;

class SmsIndexRequest extends FormRequest
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
        ];
    }
}
