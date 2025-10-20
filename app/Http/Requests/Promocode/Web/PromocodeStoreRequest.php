<?php

declare(strict_types=1);

namespace App\Http\Requests\Promocode\Web;

use Illuminate\Foundation\Http\FormRequest;

class PromocodeStoreRequest extends FormRequest
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
            'type' => ['required', 'string', 'in:discount,trial,influencer,test'],
            'text' => ['required', 'string', 'max:255'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'trial_period' => ['nullable', 'integer', 'min:0'],
            'active_from' => ['required', 'date'],
            'active_to' => ['required', 'date', 'after:active_from'],
            'is_active' => ['boolean'],
            'show_on_frontend' => ['boolean'],
            'description' => ['nullable', 'string'],
        ];
    }
}
