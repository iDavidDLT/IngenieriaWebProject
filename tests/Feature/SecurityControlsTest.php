<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductAudit;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Tests\TestCase;

class SecurityControlsTest extends TestCase
{
    use RefreshDatabase;

    public static function foreignProductRoutes(): array
    {
        return [
            'detalle' => ['GET', ''],
            'formulario' => ['GET', '/edit'],
            'actualizar PUT' => ['PUT', ''],
            'actualizar PATCH' => ['PATCH', ''],
            'eliminar' => ['DELETE', ''],
        ];
    }

    #[DataProvider('foreignProductRoutes')]
    public function test_another_user_cannot_access_or_modify_a_product(string $method, string $suffix): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $owner->id, 'name' => 'Privado']);
        $this->actingAs($other)->call($method, '/productos/'.$product->id.$suffix, [
            'name' => 'Modificado', 'sku' => $product->sku, 'price' => 1, 'stock' => 1,
        ])->assertForbidden();
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Privado', 'user_id' => $owner->id]);
        $this->assertDatabaseCount('product_audits', 0);
    }

    public function test_lists_searches_and_totals_only_include_current_users_products(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        Product::factory()->create(['user_id' => $owner->id, 'sku' => 'OWN-001', 'stock' => 2]);
        Product::factory()->create(['user_id' => $other->id, 'sku' => 'OTHER-001', 'stock' => 37]);

        $this->actingAs($owner)->get('/productos')
            ->assertOk()->assertSee('OWN-001')->assertDontSee('OTHER-001')
            ->assertViewHas('totalProducts', 1)
            ->assertViewHas('totalUnits', 2)
            ->assertViewHas('lowStock', 1);
        $this->get('/productos?q=OTHER')->assertOk()->assertDontSee('OTHER-001');
        $this->get('/productos?q=%25')->assertOk()->assertDontSee('OTHER-001');
    }

    public function test_client_cannot_choose_or_change_product_owner(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $data = ['name' => 'Producto', 'sku' => 'OWNER-001', 'price' => 2, 'stock' => 1, 'user_id' => $other->id];
        $this->actingAs($owner)->post('/productos', $data)->assertSessionHasNoErrors();
        $product = Product::where('sku', 'OWNER-001')->firstOrFail();
        $this->assertSame($owner->id, $product->user_id);

        $this->put('/productos/'.$product->id, $data)->assertSessionHasNoErrors();
        $this->assertSame($owner->id, $product->fresh()->user_id);
    }

    public function test_sku_is_normalized_and_duplicate_lowercase_sku_is_rejected(): void
    {
        $owner = User::factory()->create();
        $data = ['name' => 'Producto', 'sku' => '  lower-001  ', 'price' => 2, 'stock' => 1];
        $this->actingAs($owner)->post('/productos', $data)->assertSessionHasNoErrors();
        $this->assertDatabaseHas('products', ['sku' => 'LOWER-001']);
        $this->post('/productos', $data)->assertSessionHasErrors('sku');
        $this->assertDatabaseCount('products', 1);
    }

    public function test_arrays_instead_of_field_values_are_rejected_without_server_errors(): void
    {
        $this->actingAs(User::factory()->create())->post('/productos', [
            'name' => ['malicioso'], 'sku' => ['SKU-001'], 'price' => ['1'], 'stock' => ['1'],
        ])->assertSessionHasErrors(['name', 'sku', 'price', 'stock']);
        $this->assertDatabaseCount('products', 0);
    }

    public function test_mutations_create_audit_records_and_delete_preserves_history(): void
    {
        $owner = User::factory()->create();
        $this->actingAs($owner);
        $data = ['name' => 'Producto auditado', 'sku' => 'AUDIT-001', 'price' => 2, 'stock' => 1];
        $this->post('/productos', $data)->assertSessionHasNoErrors();
        $product = Product::where('sku', 'AUDIT-001')->firstOrFail();
        $this->put('/productos/'.$product->id, array_merge($data, ['stock' => 3]))->assertSessionHasNoErrors();
        $this->delete('/productos/'.$product->id)->assertRedirect(route('productos.index'));

        foreach (['create', 'update', 'delete'] as $action) {
            $this->assertDatabaseHas('product_audits', [
                'user_id' => $owner->id, 'product_id' => $product->id, 'action' => $action, 'sku' => 'AUDIT-001',
            ]);
        }
        $this->assertDatabaseCount('product_audits', 3);
        $this->get('/actividad')->assertOk()->assertSee('AUDIT-001');
    }

    public function test_audit_page_requires_login_and_never_exposes_another_users_history(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        ProductAudit::create(['user_id' => $other->id, 'product_id' => 99, 'action' => 'delete', 'sku' => 'PRIVATE-LOG', 'product_name' => 'Privado']);
        $this->get('/actividad')->assertRedirect(route('login'));
        $this->actingAs($owner)->get('/actividad')->assertOk()->assertDontSee('PRIVATE-LOG');
    }

    public function test_product_creation_rolls_back_if_audit_cannot_be_written(): void
    {
        $this->withoutExceptionHandling()->actingAs(User::factory()->create());
        ProductAudit::creating(function (): void {
            throw new RuntimeException('Fallo simulado de auditoría');
        });

        try {
            $this->post('/productos', ['name' => 'Producto', 'sku' => 'ROLLBACK-001', 'price' => 2, 'stock' => 1]);
            $this->fail('La operación debería fallar si no se guarda la auditoría.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Fallo simulado de auditoría', $exception->getMessage());
            $this->assertDatabaseCount('products', 0);
            $this->assertDatabaseCount('product_audits', 0);
        } finally {
            ProductAudit::flushEventListeners();
        }
    }

    public function test_ip_limit_cannot_be_bypassed_by_rotating_usernames(): void
    {
        for ($attempt = 0; $attempt < 20; $attempt++) {
            $this->post('/login', ['username' => 'inexistente-'.$attempt, 'password' => 'incorrecta']);
        }

        $user = User::factory()->create();
        $this->post('/login', ['username' => $user->username, 'password' => 'password'])
            ->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_md5_is_stored_once_and_used_to_validate_the_login(): void
    {
        $password = 'PasswordPrivada123!';
        $user = User::factory()->create(['password' => Hash::make($password)]);
        $this->assertSame(md5($password), $user->getRawOriginal('password'));
        $this->assertNotSame(md5(md5($password)), $user->getRawOriginal('password'));
        $this->post('/login', ['username' => $user->username, 'password' => $password])
            ->assertRedirect(route('productos.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_private_account_can_use_bcrypt_without_accepting_old_md5_hashes(): void
    {
        config(['hashing.driver' => 'bcrypt', 'security.demo_enabled' => false]);
        $user = User::factory()->create(['password' => Hash::make('PrivadaPassword123!')]);
        $this->assertSame('bcrypt', password_get_info($user->getRawOriginal('password'))['algoName']);
        $this->post('/login', ['username' => $user->username, 'password' => 'PrivadaPassword123!'])
            ->assertRedirect(route('productos.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_md5_account_is_rejected_cleanly_when_bcrypt_mode_is_active(): void
    {
        $user = User::factory()->create(['password' => md5('password')]);
        config(['hashing.driver' => 'bcrypt']);
        $this->post('/login', ['username' => $user->username, 'password' => 'password'])
            ->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_security_headers_are_sent_on_login_page(): void
    {
        $response = $this->get('/login')->assertOk();
        $response->assertHeader('X-Frame-Options', 'DENY')->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->assertStringContainsString("script-src 'self'", $response->headers->get('Content-Security-Policy'));
        $this->assertStringContainsString("frame-ancestors 'none'", $response->headers->get('Content-Security-Policy'));
    }

    public function test_demo_credentials_are_hidden_when_demo_mode_is_disabled(): void
    {
        config(['security.demo_enabled' => false]);
        $this->get('/login')->assertOk()->assertDontSee('IngenieriaWeb2026!');
        $this->seed();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_production_refuses_md5_password_storage(): void
    {
        $this->app->instance('env', 'production');
        config(['hashing.driver' => 'md5', 'security.demo_enabled' => false]);
        $this->expectException(LogicException::class);
        (new AppServiceProvider($this->app))->boot();
    }

    public function test_production_refuses_public_demo_accounts(): void
    {
        $this->app->instance('env', 'production');
        config(['hashing.driver' => 'bcrypt', 'security.demo_enabled' => true]);
        $this->expectException(LogicException::class);
        (new AppServiceProvider($this->app))->boot();
    }

    public function test_account_creation_command_hashes_password_and_does_not_offer_public_registration(): void
    {
        $password = 'PrivadaPassword123!';
        $this->artisan('users:create', ['username' => 'alumno'])
            ->expectsQuestion('Nombre', 'Alumno')
            ->expectsQuestion('Correo', 'alumno@example.test')
            ->expectsQuestion('Contraseña (12 caracteres mínimo, 72 bytes máximo)', $password)
            ->expectsQuestion('Repite la contraseña', $password)
            ->expectsOutput('Cuenta creada. El usuario tendrá su propio inventario.')
            ->assertSuccessful();

        $user = User::where('username', 'alumno')->firstOrFail();
        $this->assertSame(md5($password), $user->getRawOriginal('password'));
        $this->get('/register')->assertNotFound();
    }

    public function test_account_creation_rejects_weak_passwords(): void
    {
        $this->artisan('users:create', ['username' => 'alumno'])
            ->expectsQuestion('Nombre', 'Alumno')
            ->expectsQuestion('Correo', 'alumno@example.test')
            ->expectsQuestion('Contraseña (12 caracteres mínimo, 72 bytes máximo)', 'weak')
            ->expectsQuestion('Repite la contraseña', 'weak')
            ->assertFailed();
        $this->assertDatabaseCount('users', 0);
    }
}
