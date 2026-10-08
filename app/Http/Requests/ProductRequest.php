<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('sku'))) {
            $this->merge(['sku' => strtoupper(trim($this->input('sku')))]);
        }
    }

    public function authorize(): bool
    {
        $product = $this->route('product');

        return $this->user() !== null && ($product instanceof Product
            ? Gate::allows('update', $product)
            : Gate::allows('create', Product::class));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'sku' => ['required', 'string', 'max:30', 'alpha_dash:ascii', Rule::unique('products', 'sku')->ignore($this->route('product'))],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:9999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:1000000'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser texto.',
            'max' => 'El campo :attribute supera el límite permitido.',
            'sku.unique' => 'Este código SKU ya está registrado.',
            'sku.alpha_dash' => 'Usa letras sin tildes, números, guiones o guiones bajos en el SKU.',
            'price.numeric' => 'El precio debe ser un número.',
            'price.decimal' => 'El precio admite hasta dos decimales.',
            'price.min' => 'El precio no puede ser negativo.',
            'stock.integer' => 'La cantidad debe ser un número entero.',
            'stock.min' => 'La cantidad no puede ser negativa.',
        ];
    }

    public function attributes(): array
    {
        return ['name' => 'nombre', 'sku' => 'SKU', 'description' => 'descripción', 'price' => 'precio', 'stock' => 'cantidad'];
    }
}
