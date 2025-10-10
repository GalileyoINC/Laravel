<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'integer', 'min:1'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'products.*.price' => ['required', 'numeric', 'min:0'],
            'billing_address' => ['nullable', 'array'],
            'billing_address.first_name' => ['nullable', 'string', 'max:255'],
            'billing_address.last_name' => ['nullable', 'string', 'max:255'],
            'billing_address.email' => ['nullable', 'email', 'max:255'],
            'billing_address.phone' => ['nullable', 'string', 'max:50'],
            'billing_address.address' => ['nullable', 'string', 'max:500'],
            'billing_address.city' => ['nullable', 'string', 'max:100'],
            'billing_address.state' => ['nullable', 'string', 'max:100'],
            'billing_address.zip' => ['nullable', 'string', 'max:20'],
            'billing_address.country' => ['nullable', 'string', 'max:100'],
            'shipping_address' => ['nullable', 'array'],
            'shipping_address.first_name' => ['nullable', 'string', 'max:255'],
            'shipping_address.last_name' => ['nullable', 'string', 'max:255'],
            'shipping_address.email' => ['nullable', 'email', 'max:255'],
            'shipping_address.phone' => ['nullable', 'string', 'max:50'],
            'shipping_address.address' => ['nullable', 'string', 'max:500'],
            'shipping_address.city' => ['nullable', 'string', 'max:100'],
            'shipping_address.state' => ['nullable', 'string', 'max:100'],
            'shipping_address.zip' => ['nullable', 'string', 'max:20'],
            'shipping_address.country' => ['nullable', 'string', 'max:100'],
            'payment_method' => ['nullable', 'string', 'in:credit_card,apple_pay,google_pay'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'products.required' => 'Products are required',
            'products.array' => 'Products must be an array',
            'products.min' => 'At least one product is required',
            'products.*.id.required' => 'Product ID is required',
            'products.*.id.integer' => 'Product ID must be a number',
            'products.*.id.min' => 'Product ID must be at least 1',
            'products.*.quantity.required' => 'Product quantity is required',
            'products.*.quantity.integer' => 'Product quantity must be a number',
            'products.*.quantity.min' => 'Product quantity must be at least 1',
            'products.*.price.required' => 'Product price is required',
            'products.*.price.numeric' => 'Product price must be a number',
            'products.*.price.min' => 'Product price must be at least 0',
            'billing_address.array' => 'Billing address must be an array',
            'billing_address.first_name.string' => 'Billing first name must be text',
            'billing_address.first_name.max' => 'Billing first name cannot exceed 255 characters',
            'billing_address.last_name.string' => 'Billing last name must be text',
            'billing_address.last_name.max' => 'Billing last name cannot exceed 255 characters',
            'billing_address.email.email' => 'Billing email must be a valid email address',
            'billing_address.email.max' => 'Billing email cannot exceed 255 characters',
            'billing_address.phone.string' => 'Billing phone must be text',
            'billing_address.phone.max' => 'Billing phone cannot exceed 50 characters',
            'billing_address.address.string' => 'Billing address must be text',
            'billing_address.address.max' => 'Billing address cannot exceed 500 characters',
            'billing_address.city.string' => 'Billing city must be text',
            'billing_address.city.max' => 'Billing city cannot exceed 100 characters',
            'billing_address.state.string' => 'Billing state must be text',
            'billing_address.state.max' => 'Billing state cannot exceed 100 characters',
            'billing_address.zip.string' => 'Billing ZIP code must be text',
            'billing_address.zip.max' => 'Billing ZIP code cannot exceed 20 characters',
            'billing_address.country.string' => 'Billing country must be text',
            'billing_address.country.max' => 'Billing country cannot exceed 100 characters',
            'shipping_address.array' => 'Shipping address must be an array',
            'shipping_address.first_name.string' => 'Shipping first name must be text',
            'shipping_address.first_name.max' => 'Shipping first name cannot exceed 255 characters',
            'shipping_address.last_name.string' => 'Shipping last name must be text',
            'shipping_address.last_name.max' => 'Shipping last name cannot exceed 255 characters',
            'shipping_address.email.email' => 'Shipping email must be a valid email address',
            'shipping_address.email.max' => 'Shipping email cannot exceed 255 characters',
            'shipping_address.phone.string' => 'Shipping phone must be text',
            'shipping_address.phone.max' => 'Shipping phone cannot exceed 50 characters',
            'shipping_address.address.string' => 'Shipping address must be text',
            'shipping_address.address.max' => 'Shipping address cannot exceed 500 characters',
            'shipping_address.city.string' => 'Shipping city must be text',
            'shipping_address.city.max' => 'Shipping city cannot exceed 100 characters',
            'shipping_address.state.string' => 'Shipping state must be text',
            'shipping_address.state.max' => 'Shipping state cannot exceed 100 characters',
            'shipping_address.zip.string' => 'Shipping ZIP code must be text',
            'shipping_address.zip.max' => 'Shipping ZIP code cannot exceed 20 characters',
            'shipping_address.country.string' => 'Shipping country must be text',
            'shipping_address.country.max' => 'Shipping country cannot exceed 100 characters',
            'payment_method.string' => 'Payment method must be text',
            'payment_method.in' => 'Payment method must be one of: credit_card, apple_pay, google_pay',
            'notes.string' => 'Order notes must be text',
            'notes.max' => 'Order notes cannot exceed 1000 characters',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'products' => 'products',
            'products.*.id' => 'product ID',
            'products.*.quantity' => 'product quantity',
            'products.*.price' => 'product price',
            'billing_address' => 'billing address',
            'billing_address.first_name' => 'billing first name',
            'billing_address.last_name' => 'billing last name',
            'billing_address.email' => 'billing email',
            'billing_address.phone' => 'billing phone',
            'billing_address.address' => 'billing address',
            'billing_address.city' => 'billing city',
            'billing_address.state' => 'billing state',
            'billing_address.zip' => 'billing ZIP code',
            'billing_address.country' => 'billing country',
            'shipping_address' => 'shipping address',
            'shipping_address.first_name' => 'shipping first name',
            'shipping_address.last_name' => 'shipping last name',
            'shipping_address.email' => 'shipping email',
            'shipping_address.phone' => 'shipping phone',
            'shipping_address.address' => 'shipping address',
            'shipping_address.city' => 'shipping city',
            'shipping_address.state' => 'shipping state',
            'shipping_address.zip' => 'shipping ZIP code',
            'shipping_address.country' => 'shipping country',
            'payment_method' => 'payment method',
            'notes' => 'order notes',
        ];
    }
}