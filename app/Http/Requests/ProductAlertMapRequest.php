<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductAlertMapRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow authenticated users
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'limit' => 'sometimes|integer|min:1|max:100',
            'offset' => 'sometimes|integer|min:0',
            'severity' => 'sometimes|string|in:critical,high,medium,low',
            'category' => 'sometimes|string|max:255',
            'bounds' => 'sometimes|array',
            'bounds.north' => 'required_with:bounds|numeric|between:-90,90',
            'bounds.south' => 'required_with:bounds|numeric|between:-90,90',
            'bounds.east' => 'required_with:bounds|numeric|between:-180,180',
            'bounds.west' => 'required_with:bounds|numeric|between:-180,180',
            'filter' => 'sometimes|array',
            'filter.type' => 'sometimes|integer',
            'filter.status' => 'sometimes|integer',
            'filter.active_only' => 'sometimes|boolean',
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
            'severity.in' => 'Severity must be one of: critical, high, medium, low',
            'bounds.north.between' => 'North latitude must be between -90 and 90',
            'bounds.south.between' => 'South latitude must be between -90 and 90',
            'bounds.east.between' => 'East longitude must be between -180 and 180',
            'bounds.west.between' => 'West longitude must be between -180 and 180',
        ];
    }
}
