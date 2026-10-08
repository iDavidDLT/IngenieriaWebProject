<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    public static function protectedRoutes(): array
    {
        return [
            'listado' => ['GET', '/productos'],
            'crear formulario' => ['GET', '/productos/create'],
            'crear registro' => ['POST', '/productos'],
            'detalle' => ['GET', '/productos/1'],
            'editar formulario' => ['GET', '/productos/1/edit'],
            'actualizar' => ['PUT', '/productos/1'],
            'eliminar' => ['DELETE', '/productos/1'],
            'logout' => ['POST', '/logout'],
        ];
    }

    #[DataProvider('protectedRoutes')]
    public function test_every_crud_route_requires_authentication(string $method, string $url): void
    {
        $this->call($method, $url)->assertRedirect(route('login'));
        $this->assertDatabaseCount('products', 0);
    }

    public function test_json_requests_without_session_return_unauthorized(): void
    {
        $this->getJson('/productos')->assertUnauthorized();
    }

    public function test_login_page_is_public_and_password_is_not_rendered_in_an_input(): void
    {
        $this->get('/login')->assertOk()->assertSee('Iniciar sesión')->assertSee('type="password"', false);
    }

    public function test_wrong_credentials_do_not_authenticate(): void
    {
        $user = User::factory()->create();
        $this->post('/login', ['username' => $user->username, 'password' => 'incorrecta'])
            ->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_valid_username_and_password_authenticate_and_restore_intended_url(): void
    {
        $user = User::factory()->create();
        $this->get('/productos/create')->assertRedirect(route('login'));
        $this->post('/login', ['username' => $user->username, 'password' => 'password'])
            ->assertRedirect(route('productos.create'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_demo_password_is_md5_and_seed_can_run_twice_without_duplicates(): void
    {
        $this->seed();
        $this->seed();
        $user = User::where('username', 'admin')->firstOrFail();
        $hash = $user->getRawOriginal('password');
        $this->assertNotSame('IngenieriaWeb2026!', $hash);
        $this->assertTrue(Hash::check('IngenieriaWeb2026!', $hash));
        $this->assertSame('md5', Hash::info($hash)['algoName']);
        $this->assertArrayNotHasKey('password', $user->toArray());
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('products', 4);
    }

    public function test_login_is_limited_after_five_failed_attempts(): void
    {
        $user = User::factory()->create();
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/login', ['username' => $user->username, 'password' => 'incorrecta']);
        }
        $this->post('/login', ['username' => $user->username, 'password' => 'password'])
            ->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_create_read_update_and_delete_product(): void
    {
        $this->actingAs(User::factory()->create());
        $this->get('/productos/create')->assertOk();
        $data = ['name' => 'Teclado de prueba', 'sku' => 'TEST-001', 'price' => '25.90', 'stock' => 10, 'description' => 'Prueba CRUD'];
        $this->post('/productos', $data)->assertSessionHasNoErrors();
        $product = Product::where('sku', 'TEST-001')->firstOrFail();
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 10]);

        $this->get('/productos')->assertOk()->assertSee('Teclado de prueba');
        $this->get('/productos/'.$product->id)->assertOk()->assertSee('Prueba CRUD');
        $this->get('/productos/'.$product->id.'/edit')->assertOk();

        $this->put('/productos/'.$product->id, array_merge($data, ['name' => 'Teclado actualizado', 'stock' => 20]))
            ->assertSessionHasNoErrors()->assertRedirect(route('productos.show', $product));
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Teclado actualizado', 'stock' => 20]);

        $this->delete('/productos/'.$product->id)->assertRedirect(route('productos.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_invalid_values_and_duplicate_sku_do_not_create_records(): void
    {
        $this->actingAs(User::factory()->create());
        Product::factory()->create(['user_id' => auth()->id(), 'sku' => 'DUP-001']);
        $this->post('/productos', ['name' => '', 'sku' => 'DUP-001', 'price' => '-1', 'stock' => '1.5'])
            ->assertSessionHasErrors(['name', 'sku', 'price', 'stock']);
        $this->assertDatabaseCount('products', 1);
    }

    public function test_invalid_update_preserves_existing_data(): void
    {
        $this->actingAs(User::factory()->create());
        $product = Product::factory()->create(['user_id' => auth()->id(), 'stock' => 10]);
        $this->put('/productos/'.$product->id, ['name' => 'Cambio', 'sku' => $product->sku, 'price' => '5.123', 'stock' => -1])
            ->assertSessionHasErrors(['price', 'stock']);
        $this->assertSame(10, $product->fresh()->stock);
    }

    public function test_search_finds_names_and_sku_and_handles_empty_results(): void
    {
        $this->actingAs(User::factory()->create());
        Product::factory()->create(['user_id' => auth()->id(), 'name' => 'Monitor de prueba', 'sku' => 'MON-TEST']);
        Product::factory()->create(['user_id' => auth()->id(), 'name' => 'Teclado', 'sku' => 'TEC-TEST']);
        $this->get('/productos?q=Monitor')->assertOk()->assertSee('Monitor de prueba')->assertDontSee('TEC-TEST');
        $this->get('/productos?q=MON-TEST')->assertOk()->assertSee('Monitor de prueba');
        $this->get('/productos?q=inexistente')->assertOk()->assertSee('No encontramos productos');
    }

    public function test_product_text_is_escaped_to_prevent_script_injection(): void
    {
        $this->actingAs(User::factory()->create());
        $product = Product::factory()->create(['user_id' => auth()->id(), 'description' => '<script>alert(1)</script>']);
        $this->get('/productos/'.$product->id)->assertOk()
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_missing_product_returns_not_found(): void
    {
        $this->actingAs(User::factory()->create())->get('/productos/9999')->assertNotFound();
    }

    public function test_logout_invalidates_access_and_protected_pages_have_no_store_header(): void
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get('/productos')->assertOk();
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
        $this->get('/productos')->assertRedirect(route('login'));
    }
}
