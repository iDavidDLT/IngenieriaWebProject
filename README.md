# Comisiones por vendedor — Semana 4

Implementación del ejercicio `Video-MVC-main.zip` con Laravel 12, PHP 8.2+ y SQLite. Incluye filtro inclusivo por fechas, cálculo por venta, resumen por vendedor, detalle de cada operación y validación en español. No requiere Node, Vite ni servicios externos para mostrar la interfaz.

## Aplicación publicada

https://comisiones-mvc-semana4.onrender.com/comisiones

Desplegada y comprobada en Render (plan Free). [Evidencia de verificación](docs/VERIFICACION.md).

## Abrir en este equipo

Doble clic en **iniciar.bat**. Después abre http://127.0.0.1:8014/comisiones. El arranque genera configuración si hace falta, ejecuta migraciones y carga los datos de ejemplo solo si las tablas están vacías. No borra datos al iniciar de nuevo.

En otro equipo con PHP y Composer:

```powershell
composer install
Copy-Item .env.example .env
New-Item -ItemType File database/database.sqlite
php artisan key:generate
php artisan migrate --force
php artisan demo:preparar
php artisan serve --port=8014
```

## Demostración principal

Calcula del **2025-06-01** al **2025-06-30**.

| Vendedor | Ventas | Monto | Comisión |
|---|---:|---:|---:|
| Jossue Ayala | 2 | $1.500,00 | $124,00 |
| Melanie Jacome | 2 | $1.700,00 | $1.420,00 |
| Carlos Pérez | 1 | $300,00 | $0,00 |
| María Ortiz | 1 | $900,00 | $90,00 |
| **Total** | **6** | **$4.400,00** | **$1.634,00** |

La interfaz usa punto decimal y coma para miles: `$1,634.00`.

## Reglas conservadas del ZIP

| Umbral de una venta | Tasa |
|---:|---:|
| $0 | 0 % |
| $500 | 8 % |
| $600 | 6 % |
| $800 | 10 % |
| $1.000 | 115 % |

La tasa `1.15` significa 115 %, aunque podría ser un error de digitación del material. Se conserva deliberadamente: no hay confirmación de que deba cambiarse a 15 %. Las tasas no son necesariamente crecientes. Se selecciona el **mayor umbral menor o igual al monto de cada venta**, no el mayor porcentaje ni el total vendido por el vendedor.

Para Jossue: $650 × 6 % = $39; $850 × 10 % = $85; total $124. La venta de María del 28 de mayo ($700) queda fuera del período de junio.

## MVC y responsabilidades

- **Modelos:** `app/Models/Vendedor.php`, `Venta.php`, `Regla.php`. Tablas y relaciones Eloquent.
- **Vistas:** `resources/views/comisiones/index.blade.php` y `layouts/app.blade.php`. Formulario, tablas y mensajes. Blade escapa los textos y el formulario usa CSRF.
- **Controlador:** `app/Http/Controllers/ComisionController.php`. Obtiene catálogos, consulta ventas filtradas y devuelve la vista.
- **Validación:** `app/Http/Requests/CalcularComisionesRequest.php`. Fechas reales y fin igual o posterior al inicio.
- **Servicio:** `app/Services/CalculadoraComisiones.php`. Regla por venta, cálculo en centavos y agrupación por ID de vendedor. El servicio apoya al controlador; no sustituye los tres componentes MVC.
- **Rutas:** `GET /` redirige al ejercicio, `GET /comisiones` muestra el formulario y `POST /comisiones` calcula; `GET /up` verifica salud.
- **Persistencia:** migraciones y seeders en `database/`. Los datos originales se mantienen.

Las reglas se consultan una sola vez por cálculo. Se conserva la precisión exacta del esquema original: montos de venta enteros y porcentajes con dos decimales. Para admitir montos con centavos se tendría que ampliar el esquema y adaptar la conversión.

## Pruebas

```powershell
php artisan test --compact
```

Pruebas relevantes en `tests/Feature/ComisionesTest.php`: totales de junio, exclusión de mayo, extremos inclusivos, mismo día, vacío, fechas inválidas, todos los umbrales, arranque sin duplicar y escape de HTML.

## Nube y video

- [Despliegue en Render](docs/DESPLIEGUE-RENDER.md)
- [Guion de grabación](docs/GUION-VIDEO.md)
- [Resultados de referencia](docs/CASOS-DEMO.md)

El despliegue es un servicio web Docker con Apache, no un sitio estático. Los datos de la demo son de solo lectura; SQLite se reconstruye automáticamente con los seeders cuando Render pierde el disco efímero. Eso es adecuado para este ejercicio. Si se añadiera registro de ventas, habría que usar una base persistente.
