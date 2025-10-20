<?php

declare(strict_types=1);

namespace App\Http\Requests\Communication\Web;

use Illuminate\Foundation\Http\FormRequest;

class PhoneNumberSuperRequest extends FormRequest
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
            'type' => 'required|integer',
            'number' => 'required|string|max:20',
            'is_valid' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_primary' => 'nullable|boolean',
            'is_send' => 'nullable|boolean',
            'is_emergency_only' => 'nullable|boolean',
            'twilio_type' => 'nullable|string|max:50',
            'twilio_type_raw' => 'nullable|array',
            'numverify_type' => 'nullable|string|max:50',
            'numverify_raw' => 'nullable|array',
            'bivy_status' => 'nullable|array',
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
            'type.required' => 'The type field is required.',
            'type.integer' => 'The type must be an integer.',
            'number.required' => 'The number field is required.',
            'number.max' => 'The number may not be greater than 20 characters.',
            'is_valid.boolean' => 'The is valid field must be true or false.',
            'is_active.boolean' => 'The is active field must be true or false.',
            'is_primary.boolean' => 'The is primary field must be true or false.',
            'is_send.boolean' => 'The is send field must be true or false.',
            'is_emergency_only.boolean' => 'The is emergency only field must be true or false.',
            'twilio_type.max' => 'The twilio type may not be greater than 50 characters.',
            'twilio_type_raw.array' => 'The twilio type raw must be an array.',
            'numverify_type.max' => 'The numverify type may not be greater than 50 characters.',
            'numverify_raw.array' => 'The numverify raw must be an array.',
            'bivy_status.array' => 'The bivy status must be an array.',
        ];
    }
}
