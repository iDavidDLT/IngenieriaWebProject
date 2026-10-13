<?php
namespace App\Services;
use Illuminate\Support\Collection;
class CalculadoraComisiones
{
    
    public function calcular(Collection $ventas, Collection $reglas): array
    {
        $ordenadas = $reglas->sortByDesc('umbral_monto');
        $resultados = []; $detalle = []; $totalVentas = 0; $totalComisiones = 0;
        foreach ($ventas as $venta) {
            $regla = $ordenadas->first(fn ($regla) => $regla->umbral_monto <= $venta->monto);
            
            $tasa = (int) round((float) ($regla?->porcentaje ?? 0) * 100);
            $centavos = (int) $venta->monto * $tasa;
            $id = $venta->vendedor_id;
            if (!isset($resultados[$id])) {
                $resultados[$id] = ['nombre' => $venta->vendedor->nombre, 'cantidad' => 0,
                    'ventas_centavos' => 0, 'comision_centavos' => 0];
            }
            $resultados[$id]['cantidad']++;
            $resultados[$id]['ventas_centavos'] += (int) $venta->monto * 100;
            $resultados[$id]['comision_centavos'] += $centavos;
            $totalVentas += (int) $venta->monto * 100;
            $totalComisiones += $centavos;
            $detalle[] = ['venta' => $venta, 'umbral' => $regla?->umbral_monto ?? 0,
                'porcentaje' => $tasa, 'comision_centavos' => $centavos];
        }
        return compact('resultados', 'detalle', 'totalVentas', 'totalComisiones');
    }
}
