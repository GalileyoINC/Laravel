<?php

declare(strict_types=1);

namespace App\Http\Requests\Service\Web;

use Illuminate\Foundation\Http\FormRequest;

class ServiceSettingsRequest extends FormRequest
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
            'service__year_discount' => 'nullable|numeric|min:0|max:100',
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
            'service__year_discount.numeric' => 'The year discount must be a number.',
            'service__year_discount.min' => 'The year discount must be at least 0.',
            'service__year_discount.max' => 'The year discount must not be greater than 100.',
        ];
    }
}
