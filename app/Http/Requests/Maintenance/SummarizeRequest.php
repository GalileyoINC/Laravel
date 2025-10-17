<?php

declare(strict_types=1);

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class SummarizeRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'size' => 'required|integer|min:1|max:10000',
            'text' => 'required|string|min:1|max:50000',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'size.required' => 'Size is required',
            'size.integer' => 'Size must be an integer',
            'size.min' => 'Size must be at least 1 character',
            'size.max' => 'Size must not exceed 10000 characters',
            'text.required' => 'Text is required',
            'text.string' => 'Text must be a string',
            'text.min' => 'Text must be at least 1 character',
            'text.max' => 'Text must not exceed 50000 characters',
        ];
    }
}
