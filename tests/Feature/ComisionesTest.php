<?php
namespace Tests\Feature;
use App\Models\Regla;
use App\Models\Vendedor;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ComisionesTest extends TestCase
{
    use RefreshDatabase;
    public function test_portada_redirige_al_ejercicio(): void
    {
        $this->get('/')->assertRedirect('/comisiones');
    }
    public function test_formulario_muestra_catalogos_originales(): void
    {
        $this->seed();
        $this->get('/comisiones')->assertOk()->assertSee('Cada venta, una comisión.')
            ->assertSee('Jossue Ayala')->assertSee('115 %')->assertSee('7 ventas');
    }
    public function test_junio_se_calcula_por_venta_y_excluye_mayo(): void
    {
        $this->seed();
        $this->post('/comisiones', ['fecha_inicio'=>'2025-06-01','fecha_fin'=>'2025-06-30'])
            ->assertOk()->assertViewHas('totalVentas', 440000)->assertViewHas('totalComisiones', 163400)
            ->assertViewHas('detalle', fn ($filas) => count($filas) === 6)
            ->assertViewHas('resultados', fn ($r) => $r[1]['comision_centavos'] === 12400
                && $r[2]['comision_centavos'] === 142000 && $r[3]['comision_centavos'] === 0
                && $r[4]['comision_centavos'] === 9000)->assertSee('$1,634.00');
    }
    public function test_rango_incluye_ambos_extremos(): void
    {
        $this->seed();
        $this->post('/comisiones', ['fecha_inicio'=>'2025-06-02','fecha_fin'=>'2025-06-15'])
            ->assertOk()->assertViewHas('detalle', fn ($f) => count($f) === 5)
            ->assertViewHas('totalVentas', 320000)->assertViewHas('totalComisiones', 25400);
    }
    public function test_mismo_dia_y_sin_ventas(): void
    {
        $this->seed();
        $this->post('/comisiones', ['fecha_inicio'=>'2025-06-05','fecha_fin'=>'2025-06-05'])
            ->assertOk()->assertViewHas('totalComisiones', 4000);
        $this->post('/comisiones', ['fecha_inicio'=>'2026-01-01','fecha_fin'=>'2026-01-31'])
            ->assertOk()->assertViewHas('totalComisiones', 0)->assertSee('No hay ventas en este rango.');
    }
    public function test_fecha_final_anterior_es_rechazada(): void
    {
        $this->from('/comisiones')->post('/comisiones', ['fecha_inicio'=>'2025-06-30','fecha_fin'=>'2025-06-01'])
            ->assertRedirect('/comisiones')->assertSessionHasErrors('fecha_fin');
    }
    public function test_fechas_faltantes_o_imposibles_son_rechazadas(): void
    {
        $this->post('/comisiones', [])->assertSessionHasErrors(['fecha_inicio','fecha_fin']);
        $this->post('/comisiones', ['fecha_inicio'=>'2025-02-30','fecha_fin'=>'2025-03-10'])
            ->assertSessionHasErrors('fecha_inicio');
    }
    public function test_cada_umbral_se_aplica_desde_el_limite_exacto(): void
    {
        $this->seed();
        Venta::query()->delete();
        foreach ([499,500,599,600,799,800,999,1000] as $monto) {
            Venta::create(['vendedor_id'=>1,'fecha_venta'=>'2025-07-01','monto'=>$monto]);
        }
        $this->post('/comisiones', ['fecha_inicio'=>'2025-07-01','fecha_fin'=>'2025-07-01'])
            ->assertOk()->assertViewHas('detalle', fn ($f) => array_column($f, 'porcentaje') === [0,8,8,6,6,10,10,115]);
    }
    public function test_demo_preparar_no_duplica_datos(): void
    {
        $this->artisan('demo:preparar')->assertSuccessful();
        $this->artisan('demo:preparar')->assertSuccessful();
        $this->assertDatabaseCount('vendedores', 4);
        $this->assertDatabaseCount('reglas', 5);
        $this->assertDatabaseCount('ventas', 7);
    }
    public function test_escapa_html_en_nombre_del_vendedor(): void
    {
        $this->seed();
        Vendedor::find(1)->update(['nombre'=>'<script>alert(1)</script>']);
        $this->get('/comisiones')->assertOk()->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }
}
