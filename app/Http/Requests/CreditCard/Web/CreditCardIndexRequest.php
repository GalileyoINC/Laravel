<?php

declare(strict_types=1);

namespace App\Http\Requests\CreditCard\Web;

use Illuminate\Foundation\Http\FormRequest;

class CreditCardIndexRequest extends FormRequest
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
            'type' => ['nullable', 'string', 'max:50'],
            'expiration_year' => ['nullable', 'integer'],
            'user_id' => ['nullable', 'integer'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
            'updated_at_from' => ['nullable', 'date'],
            'updated_at_to' => ['nullable', 'date'],
        ];
    }
}
