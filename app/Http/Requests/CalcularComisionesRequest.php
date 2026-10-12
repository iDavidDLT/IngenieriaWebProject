<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class CalcularComisionesRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'fecha_inicio' => ['required', 'date_format:Y-m-d'],
            'fecha_fin' => ['required', 'date_format:Y-m-d', 'after_or_equal:fecha_inicio'],
        ];
    }
    public function messages(): array
    {
        return [
            'fecha_inicio.required' => 'Selecciona la fecha de inicio.',
            'fecha_fin.required' => 'Selecciona la fecha de fin.',
            'fecha_inicio.date_format' => 'La fecha de inicio debe ser válida (AAAA-MM-DD).',
            'fecha_fin.date_format' => 'La fecha de fin debe ser válida (AAAA-MM-DD).',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la de inicio.',
        ];
    }
}
