<?php

declare(strict_types=1);

namespace App\Http\Requests\Device\Web;

use Illuminate\Foundation\Http\FormRequest;

class PushRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'data' => 'nullable|string',
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
            'title.required' => 'The title field is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'body.required' => 'The body field is required.',
            'body.max' => 'The body may not be greater than 1000 characters.',
        ];
    }
}
