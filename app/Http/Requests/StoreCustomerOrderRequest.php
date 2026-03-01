<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'shipping_address' => 'required|array',
            'shipping_address.name' => 'required|string',
            'shipping_address.email' => 'required|email',
            'shipping_address.phone' => 'nullable|string',
            'shipping_address.address' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.postal_code' => 'required|string',
            'shipping_address.country' => 'required|string',
            'notes' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Please add at least one product to your order.',
            'items.min' => 'Please add at least one product to your order.',
            'items.*.product_id.required' => 'Product information is missing for one or more items.',
            'items.*.quantity.min' => 'Quantity for each product must be at least 1.',
            'shipping_address.name.required' => 'Recipient name is required.',
            'shipping_address.email.required' => 'Recipient email is required.',
            'shipping_address.address.required' => 'Address is required.',
            'shipping_address.city.required' => 'City is required.',
            'shipping_address.postal_code.required' => 'Postal code is required.',
            'shipping_address.country.required' => 'Country is required.',
        ];
    }
}
