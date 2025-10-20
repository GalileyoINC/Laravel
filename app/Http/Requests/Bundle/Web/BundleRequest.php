<?php

declare(strict_types=1);

namespace App\Http\Requests\Bundle\Web;

use Illuminate\Foundation\Http\FormRequest;

class BundleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
     */
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $bundleId = $this->route('bundle')->id ?? null;

        return [
            'title' => [
                'required',
                'string',
                'max:255',
                'unique:bundle,title,'.$bundleId,
            ],
            'type' => ['nullable', 'integer'],
            'pay_interval' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'total' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors
     */
    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'title.unique' => 'The title has already been taken.',
            'type.integer' => 'The type must be an integer.',
            'pay_interval.integer' => 'The pay interval must be an integer.',
            'is_active.boolean' => 'The is active field must be true or false.',
            'total.required' => 'The total is required.',
            'total.numeric' => 'The total must be a number.',
            'total.min' => 'The total must be at least 0.',
        ];
    }

    /**
     * Get custom attributes for validator errors
     */
    /**
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return [
            'title' => 'title',
            'type' => 'type',
            'pay_interval' => 'pay interval',
            'is_active' => 'is active',
            'total' => 'total',
        ];
    }
}
