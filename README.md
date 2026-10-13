# Comisiones MVC

Aplicación Laravel para calcular comisiones por vendedor a partir de las ventas de un período.

**Aplicación:** https://comisiones-mvc-semana4.onrender.com/comisiones

## Funcionalidades

- Filtro por fecha de inicio y fin, incluyendo ambos extremos.
- Selección de la regla por el monto de cada venta.
- Resumen por vendedor y totales del período.
- Detalle de monto, umbral, tasa y comisión aplicada.
- Validación de fechas y mensajes en español.

## Tecnologías

PHP 8.2 o posterior, Laravel 12, Blade y SQLite. El despliegue utiliza Docker y Apache en Render. Los estilos se sirven desde public/css y no requieren un proceso de compilación.

## Ejecución local

```powershell
composer install
Copy-Item .env.example .env
New-Item -ItemType File database/database.sqlite
php artisan key:generate
php artisan migrate --force
php artisan demo:preparar
php artisan serve --port=8014
```

Abrir http://127.0.0.1:8014/comisiones. En un equipo con las dependencias ya instaladas también se puede usar iniciar.bat.

## Arquitectura

| Componente | Ubicación | Responsabilidad |
|---|---|---|
| Modelos | app/Models | Datos y relaciones Eloquent |
| Controlador | app/Http/Controllers/ComisionController.php | Consulta y coordinación de la respuesta |
| Validación | app/Http/Requests/CalcularComisionesRequest.php | Validar el período solicitado |
| Servicio | app/Services/CalculadoraComisiones.php | Selección de reglas y cálculo de comisiones |
| Vista | resources/views/comisiones/index.blade.php | Formulario, resultados y detalle |
| Rutas | routes/web.php | Acceso al formulario y envío del cálculo |

## Reglas de comisión

| Monto mínimo de una venta | Tasa |
|---:|---:|
| $0 | 0 % |
| $500 | 8 % |
| $600 | 6 % |
| $800 | 10 % |
| $1.000 | 115 % |

Se aplica el mayor umbral que no supera el monto de cada venta. Las tasas no son necesariamente crecientes. El valor 1.15 representa 115 %. Los montos de venta son dólares enteros; las comisiones se acumulan en centavos.

Para junio de 2025, los datos incluidos suman seis ventas, $4.400 de monto vendido y $1.634 de comisiones. La venta del 28 de mayo queda fuera del período.

## Pruebas

```powershell
php artisan test --compact
```

Se verifican los cálculos por vendedor, umbrales, extremos del período, validación, resultados vacíos y carga de datos sin duplicación.

## Despliegue

Dockerfile instala PHP, Apache y dependencias de producción. docker/start.sh adapta el puerto, ejecuta migraciones, prepara los datos y genera las cachés. render.yaml proporciona una configuración reproducible del servicio.

La clave APP_KEY se configura como variable de entorno. No se incluye en el repositorio. En producción se utiliza APP_ENV=production y APP_DEBUG=false.

Los datos de esta aplicación son de demostración y solo se consultan. SQLite se reconstruye con los datos iniciales si desaparece el disco efímero de Render. Para guardar nuevas ventas sería necesario utilizar almacenamiento persistente.
