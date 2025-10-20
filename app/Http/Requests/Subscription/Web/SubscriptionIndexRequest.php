<?php

declare(strict_types=1);

namespace App\Http\Requests\Subscription\Web;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionIndexRequest extends FormRequest
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
            'idCategory' => ['nullable', 'integer'],
            'search' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
            'is_custom' => ['nullable', 'integer', 'in:0,1'],
            'show_reactions' => ['nullable', 'integer', 'in:0,1'],
            'show_comments' => ['nullable', 'integer', 'in:0,1'],
            'sended_at_from' => ['nullable', 'date'],
            'sended_at_to' => ['nullable', 'date'],
        ];
    }
}
