<?php

declare(strict_types=1);

namespace App\Http\Requests\Communication\Web;

use Illuminate\Foundation\Http\FormRequest;

class SmsPhoneNumberRequest extends FormRequest
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
            'message' => 'required|string|max:1000',
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
            'message.required' => 'The message field is required.',
            'message.max' => 'The message may not be greater than 1000 characters.',
        ];
    }
}
