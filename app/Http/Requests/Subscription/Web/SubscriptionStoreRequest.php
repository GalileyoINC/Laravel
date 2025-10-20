<?php

declare(strict_types=1);

namespace App\Http\Requests\Subscription\Web;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionStoreRequest extends FormRequest
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
            'id_subscription_category' => ['required', 'integer', 'exists:subscription_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'alias' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_custom' => ['boolean'],
            'show_reactions' => ['boolean'],
            'show_comments' => ['boolean'],
            'imageFile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }
}
