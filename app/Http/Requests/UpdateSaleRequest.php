<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:pharmaceutical_products,id'],
            'products.*.quantite' => ['required', 'integer', 'min:1'],
            'date_vente' => ['nullable', 'date'],
        ];
    }
}
