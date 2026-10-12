<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalcularComisionesRequest;
use App\Models\Regla;
use App\Models\Vendedor;
use App\Models\Venta;
use App\Services\CalculadoraComisiones;
use Illuminate\Contracts\View\View;

class ComisionController extends Controller
{
    public function index(): View
    {
        return view('comisiones.index', $this->catalogos());
    }

    public function calcular(CalcularComisionesRequest $request, CalculadoraComisiones $calculadora): View
    {
        $fechas = $request->validated();
        $catalogos = $this->catalogos();
        $ventas = Venta::with('vendedor')->whereBetween('fecha_venta', [
            $fechas['fecha_inicio'], $fechas['fecha_fin'],
        ])->orderBy('fecha_venta')->orderBy('id')->get();
        return view('comisiones.index', array_merge($catalogos, $fechas,
            $calculadora->calcular($ventas, $catalogos['reglas'])));
    }

    private function catalogos(): array
    {
        return [
            'vendedores' => Vendedor::orderBy('id')->get(),
            'reglas' => Regla::orderBy('umbral_monto')->get(),
            'ventasEjemplo' => Venta::with('vendedor')->orderBy('fecha_venta')->get(),
        ];
    }
}
