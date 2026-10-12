# Guion para grabar (5–7 minutos)

Sustituye tu nombre y usa la URL pública real de Render. Este guion es una ayuda: explica con tus palabras el código que estás mostrando.

## 0:00–0:35 — Presentación

En pantalla: la aplicación desplegada y la barra de dirección HTTPS.

«Soy [tu nombre]. Para la semana cuatro apliqué el ejercicio de comisiones de semestres pasados, usando el repositorio proporcionado. Está desarrollado con Laravel y el patrón Modelo–Vista–Controlador. Se calcula la comisión de cada vendedor a partir de sus ventas y un período de fechas.»

## 0:35–1:45 — Funcionamiento

Muestra vendedores y reglas. Deja del 1 al 30 de junio de 2025 y pulsa Calcular comisiones.

«El período incluye ambas fechas. En junio tenemos seis ventas, un monto de cuatro mil cuatrocientos dólares y mil seiscientos treinta y cuatro dólares en comisiones. Cada comisión se calcula por venta: se elige el mayor umbral que no supera el monto, y luego se agrupan las comisiones del vendedor.»

Señala el detalle de Jossue.

«La venta de 650 dólares aplica el umbral 600 y una tasa del seis por ciento: genera 39 dólares. La de 850 aplica el diez por ciento: genera 85 dólares. La suma es 124 dólares. La venta de María en mayo no se incluye en junio.»

Señala la nota sobre las reglas.

«Conservé las reglas exactas del código entregado. Su valor 1.15 equivale a ciento quince por ciento; por eso la venta de 1.200 genera 1.380 de comisión. La aplicación lo hace visible para no confundirlo con quince por ciento. También se conserva la regla de ocho por ciento en 500 y seis por ciento en 600.»

## 1:45–2:25 — Validaciones

Cambia el período a enero de 2026: no hay ventas. Luego coloca inicio 2025-06-30 y fin 2025-06-01 para mostrar el error en español. Vuelve a junio.

«Un período sin registros devuelve un resultado vacío. El servidor valida que las fechas sean reales y que el fin no sea anterior al inicio. El formulario además usa protección CSRF.»

## 2:25–4:20 — Código y MVC

Abre el código en el editor; muestra cada archivo brevemente y señala las líneas correspondientes.

1. `routes/web.php`: GET muestra el formulario, POST envía a calcular.
2. `app/Models/Vendedor.php`, `Venta.php`, `Regla.php`: explica que representan las tablas; Venta pertenece a Vendedor y Vendedor tiene muchas ventas.
3. `app/Http/Requests/CalcularComisionesRequest.php`: muestra las reglas de las dos fechas.
4. `app/Http/Controllers/ComisionController.php`: muestra `whereBetween`, `with('vendedor')` y `return view`.
5. `app/Services/CalculadoraComisiones.php`: muestra orden descendente de umbrales, elección de la regla, cálculo en centavos y agrupación por vendedor. Explica que se consultan las reglas una vez y que el servicio concentra la operación matemática.
6. `resources/views/comisiones/index.blade.php`: muestra `@csrf`, `@error` y el recorrido de resultados. La vista recibe datos y los presenta.

«El modelo maneja datos y relaciones, el controlador coordina la petición y la vista muestra la respuesta. El servicio permite mantener el cálculo separado de la presentación y comprobarlo con pruebas.»

## 4:20–5:15 — Base de datos y pruebas

Muestra las tres migraciones y los seeders. Ejecuta `php artisan test --compact` (en este equipo usa el PHP de `.tools` si PHP no está en PATH).

«Las migraciones definen las tablas y la clave foránea de ventas. Los seeders mantienen los datos originales. Las pruebas comprueban los totales, los límites de las tasas, la inclusión de fechas, los períodos vacíos y las validaciones.»

## 5:15–6:15 — Despliegue

Muestra `Dockerfile`, `render.yaml` y el panel de Render con el servicio Live. No muestres APP_KEY ni otras credenciales.

«El despliegue se ejecuta en Render con Docker, PHP y Apache. Apache expone la carpeta public. Al arrancar se aplican las migraciones y se cargan los datos de ejemplo. La configuración de producción desactiva la depuración y reconoce HTTPS. Esta aplicación usa datos de demostración de solo lectura; se reconstruyen si el disco efímero del plan gratuito se reinicia.»

Regresa a la URL pública, recarga y calcula junio una vez más.

«Con esto se verifica la separación MVC y que el ejercicio funciona desde la nube.»

## Antes de grabar

- Abre la aplicación de Render y espera que despierte; calcula junio antes de iniciar la captura.
- Ten listos el navegador, el editor y la terminal con la carpeta del proyecto.
- Usa tu nombre y comprueba el sonido. No presentes localhost como despliegue en la nube.
- Si se decide cambiar 115 % a 15 % por confirmación docente, modifica la regla, las pruebas y los resultados del guion antes de grabar.
