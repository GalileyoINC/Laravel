<?php

declare(strict_types=1);

namespace App\Http\Requests\Users\Web;

use Illuminate\Foundation\Http\FormRequest;

class UserPlanRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'end_at' => 'required|date|after:today',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'end_at.required' => 'The end date field is required.',
            'end_at.date' => 'The end date must be a valid date.',
            'end_at.after' => 'The end date must be after today.',
        ];
    }
}
