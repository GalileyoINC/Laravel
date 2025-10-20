<?php

declare(strict_types=1);

namespace App\Http\Requests\Subscription\Web;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'id_parent' => 'nullable|integer|exists:subscription_category,id',
            'position_no' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'id_parent.integer' => 'The parent ID must be an integer.',
            'id_parent.exists' => 'The selected parent category does not exist.',
            'position_no.integer' => 'The position number must be an integer.',
            'position_no.min' => 'The position number must be at least 0.',
        ];
    }
}
