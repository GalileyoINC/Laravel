<?php

declare(strict_types=1);

namespace App\Http\Requests\SmsPool\Web;

use Illuminate\Foundation\Http\FormRequest;

class SmsPoolIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'purpose' => ['nullable', 'string', 'max:255'],
            'id_subscription' => ['nullable', 'integer'],
            'created_at' => ['nullable', 'date'],
        ];
    }
}
