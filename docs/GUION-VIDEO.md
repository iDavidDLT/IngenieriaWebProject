# Guion de grabación: qué mostrar y qué decir

Duración orientativa: 8–10 minutos. Puedes leer las frases entre comillas como apoyo, pero ensaya una vez para explicar con naturalidad. Los números de línea corresponden al código actual del proyecto.

## Preparación antes de grabar

1. Abre la aplicación: https://comisiones-mvc-semana4.onrender.com/comisiones. Espera a que cargue antes de iniciar la grabación.
2. Abre en tu editor esta carpeta: `C:\Users\ASUS\Documents\Ingenieria Web\semana4-mvc\Video-MVC-main`.
3. Ten otra pestaña del navegador con el panel de Render: https://dashboard.render.com/web/srv-db6ln4c9v7es7380pn6g.
4. Aumenta el tamaño del texto del editor hasta que se lea en el video. Activa el ajuste de línea (Word Wrap) para mostrar las vistas Blade, que tienen líneas largas. Si usas VS Code, Alt+Z alterna el ajuste; Ctrl+P abre archivos y Ctrl+G permite ir a una línea.
5. Abre la terminal en la carpeta del proyecto. Los comandos de abajo funcionan en PowerShell.
6. Graba navegador, editor y terminal. No hace falta mostrar la instalación de dependencias. No abras `.env` ni las variables secretas de Render.

## 1. Presentación — 0:00 a 0:30

**Dónde:** navegador, aplicación publicada.

**Qué mostrar:** la barra de dirección con el dominio `onrender.com` y la pantalla inicial con el formulario.

**Qué decir:**

«Hola, soy [tu nombre]. En esta actividad de la semana cuatro implementé el ejercicio MVC de comisiones por vendedor, a partir del código de semestres pasados. La aplicación utiliza Laravel, vistas Blade y una base de datos SQLite. Además, está desplegada en Render, por lo que se puede utilizar desde una URL pública.»

## 2. Demostrar el resultado — 0:30 a 1:35

**Dónde:** la misma aplicación.

**Acciones:**

1. Señala el formulario, el listado de vendedores y la tabla de reglas.
2. Coloca inicio **01/06/2025** y fin **30/06/2025**.
3. Pulsa **Calcular comisiones**.
4. Señala las tarjetas de $4,400.00 y $1,634.00.
5. Baja al detalle y señala las dos ventas de Jossue.

**Qué decir:**

«El usuario selecciona un rango de fechas. La aplicación busca las ventas de ese período y calcula las comisiones de cada vendedor. Ambas fechas están incluidas. Para junio de 2025 tenemos seis operaciones, cuatro mil cuatrocientos dólares vendidos y mil seiscientos treinta y cuatro dólares de comisiones.»

«El cálculo se hace por cada venta. Por ejemplo, Jossue tiene una venta de 650 dólares. El mayor umbral que no supera ese monto es 600, cuya tasa es seis por ciento. Esa venta genera 39 dólares. Su venta de 850 aplica el umbral 800, con diez por ciento, y genera 85 dólares. Por eso su comisión total es 124 dólares.»

**Luego señala la nota de las reglas y la venta de Melanie de $1,200.00:**

«Conservé las reglas del repositorio original. En él aparece la tasa 1.15, que matemáticamente equivale a ciento quince por ciento. Por eso esta venta de 1.200 genera 1.380 de comisión. No se interpreta como quince por ciento. También se conserva la tasa de ocho por ciento desde 500 y la de seis por ciento desde 600.»

## 3. Mostrar la estructura MVC — 1:35 a 2:00

**Dónde:** editor, explorador de archivos del proyecto.

**Qué mostrar:** las carpetas `app/Models`, `app/Http/Controllers` y `resources/views`.

**Qué decir:**

«La estructura separa tres responsabilidades. Los modelos representan los datos y sus relaciones. El controlador recibe la petición y coordina la consulta y el cálculo. La vista muestra el formulario y los resultados. Además, hay una clase de validación y un servicio que concentra la operación matemática.»

## 4. Explicar las rutas — 2:00 a 2:25

**Abre:** `routes/web.php`.

**Señala:** líneas 4, 5 y 6; el archivo completo cabe en pantalla.

**Qué decir:**

«La ruta inicial redirige a comisiones. La ruta GET de comisiones llama al método index del controlador y muestra el formulario. La ruta POST llama a calcular cuando el usuario envía las fechas. Los nombres de las rutas permiten que el formulario apunte al controlador sin escribir la dirección de forma fija.»

**Código que debes señalar:**

```php
Route::get('/comisiones', [ComisionController::class, 'index']);
Route::post('/comisiones', [ComisionController::class, 'calcular']);
```

Los fragmentos del guion son extractos para señalar; no debes pegarlos ni reemplazar el código del proyecto.

## 5. Explicar modelos y base de datos — 2:25 a 3:25

**Primero abre:** `app/Models/Vendedor.php`, líneas 9–14.

**Qué decir:**

«El modelo Vendedor usa la tabla vendedores y trabaja con nombre y correo. La relación hasMany indica que un vendedor puede tener muchas ventas.»

**Después abre:** `app/Models/Venta.php`, líneas 9–13.

**Qué decir:**

«Venta contiene el identificador del vendedor, la fecha y el monto. La relación belongsTo indica que cada venta pertenece a un vendedor. Esto permite acceder a su nombre desde una venta.»

**Después abre:** `app/Models/Regla.php`, línea 9.

**Qué decir:**

«Regla representa el umbral de monto y el porcentaje que se utilizará para calcular la comisión.»

**Abre:** `database/migrations/2025_06_29_013409_create_ventas_table.php`, líneas 15–22.

**Señala:** `foreignId`, `constrained`, `date` e `integer`.

**Qué decir:**

«Las migraciones definen las tablas. Aquí se crea ventas, con una clave foránea que la relaciona con vendedores, una fecha y un monto. En el esquema original los montos son dólares enteros.»

**Abre:** `database/seeders/VentasTableSeeder.php`, líneas 12–22.

**Qué decir:**

«Los seeders cargan los datos de ejemplo del ejercicio. Aquí están las siete ventas originales. Esta venta de María es del 28 de mayo y por eso no entra en el cálculo de junio.»

## 6. Explicar la validación — 3:25 a 4:00

**Abre:** `app/Http/Requests/CalcularComisionesRequest.php`.

**Señala primero:** líneas 10–11.

**Qué decir:**

«Antes de calcular, Laravel valida las fechas mediante este FormRequest. Required exige que se envíen los dos campos. Date format exige una fecha válida en formato año, mes y día. After or equal exige que el fin sea igual o posterior al inicio.»

**Señala después:** líneas 17–21.

**Qué decir:**

«Los mensajes se personalizan en español para que el usuario entienda qué debe corregir. Si la petición es inválida, no llega a ejecutar el cálculo.»

## 7. Explicar el controlador — 4:00 a 4:55

**Abre:** `app/Http/Controllers/ComisionController.php`.

**Señala:** líneas 14–16, el método `index`.

**Qué decir:**

«Index obtiene los catálogos y devuelve la vista inicial. La vista recibe vendedores, reglas y las ventas de ejemplo.»

**Señala:** líneas 19–25, el método `calcular`.

**Qué decir:**

«Calcular recibe la petición validada y el servicio de comisiones. Validated devuelve las fechas que cumplieron las reglas. With vendedor carga la relación del vendedor junto con las ventas. WhereBetween filtra las ventas entre la fecha de inicio y la de fin, incluyendo los extremos. Después se ordenan los registros y get ejecuta la consulta.»

**Señala:** líneas 26–27, y después líneas 33–35.

**Qué decir:**

«El controlador envía las ventas y las reglas al servicio. Finalmente combina los catálogos, las fechas y los resultados para devolver la vista. Las reglas se obtienen una sola vez para el cálculo, en lugar de consultar la base por cada venta.»

## 8. Explicar la lógica de comisiones — 4:55 a 6:10

**Abre:** `app/Services/CalculadoraComisiones.php`.

**Señala:** líneas 9–12.

**Qué decir:**

«Aquí está la lógica principal. Primero ordeno los umbrales de mayor a menor. Después recorro cada venta y tomo la primera regla cuyo umbral es menor o igual al monto. Como están ordenadas, esa regla corresponde al mayor umbral permitido para la venta. No se selecciona el porcentaje más alto.»

**Señala:** líneas 14–15.

**Qué decir:**

«El porcentaje se convierte a puntos porcentuales. Por ejemplo, 0.06 pasa a seis. Como los montos del ejercicio son dólares enteros, 650 por seis da 3.900 centavos, que equivalen a 39 dólares. Trabajar en centavos permite acumular estos resultados como enteros.»

**Señala:** líneas 16–23.

**Qué decir:**

«Agrupo los resultados por el identificador del vendedor. Si todavía no existe su registro de resultados, lo inicializo. Después acumulo el número de ventas, el monto vendido y la comisión. Cada venta usa su propia tasa; no aplico una sola tasa sobre todo lo que vendió la persona.»

**Señala:** líneas 24–29.

**Qué decir:**

«También acumulo los totales del período y guardo un detalle con la venta, el umbral aplicado, la tasa y la comisión. Eso explica cada resultado en la interfaz. Al final devuelvo estas estructuras al controlador.»

## 9. Explicar la vista — 6:10 a 6:55

**Abre:** `resources/views/comisiones/index.blade.php`. Usa ajuste de línea y señala un bloque cada vez.

**Señala:** líneas 1–2.

**Qué decir:**

«La vista usa Blade. Extends reutiliza la plantilla general y section define el contenido de esta pantalla.»

**Señala:** línea 5, el formulario POST, `route('comisiones.calcular')` y `@csrf`; después líneas 6–9.

**Qué decir:**

«El formulario envía las fechas a la ruta de cálculo. CSRF agrega el token de protección de Laravel. Old conserva lo que escribió el usuario cuando hay un error, y error muestra el mensaje que devuelve la validación.»

**Señala:** línea 16, `@forelse`, `@empty` y `number_format`; después línea 18, el detalle.

**Qué decir:**

«Forelse recorre los resultados; empty muestra el mensaje si no hay ventas. Las comisiones llegan en centavos, se dividen entre cien y se muestran con dos decimales. Esta otra tabla recorre el detalle calculado por el servicio. La vista presenta los datos; la selección de tasas ya ocurrió antes.»

## 10. Demostrar errores y pruebas — 6:55 a 7:45

**Vuelve al navegador y realiza estas acciones:**

1. Inicio **01/01/2026**, fin **31/01/2026**, y pulsa Calcular. Muestra «No hay ventas en este rango».
2. Inicio **30/06/2025**, fin **01/06/2025**, y pulsa Calcular. Muestra el error de fecha final.

**Qué decir:**

«Si no existen ventas, la aplicación muestra un resultado vacío con totales de cero. Si invierto las fechas, el servidor rechaza la petición y muestra el mensaje de validación.»

**Abre:** `tests/Feature/ComisionesTest.php`, líneas 21–29.

**Qué decir:**

«Esta prueba verifica los totales de junio y las comisiones de cada vendedor. Los valores se comprueban en centavos. También hay pruebas de los umbrales exactos, de fechas inválidas, de períodos vacíos y de carga de datos sin duplicados.»

**Dónde:** terminal PowerShell dentro de la carpeta del proyecto.

**Ejecuta:**

```powershell
& "../../.tools/php8425/php.exe" artisan test --compact
```

**Qué mostrar:** el resultado `12 passed` y `43 assertions`.

**Qué decir:**

«Al ejecutar las pruebas, los doce casos pasan y se completan cuarenta y tres comprobaciones.»

## 11. Explicar y demostrar el despliegue — 7:45 a 8:45

**Primero abre:** `Dockerfile`.

**Señala:** línea 1; líneas 6–9; línea 15.

**Qué decir:**

«Para desplegar Laravel se utiliza este contenedor con PHP 8.4 y Apache. Composer instala las dependencias de producción. El contenedor copia el proyecto y ejecuta el script de arranque. Apache sirve la carpeta public, que es el punto de entrada de Laravel.»

**Abre:** `docker/start.sh`, líneas 5–8 y 16–22.

**Qué decir:**

«El script adapta Apache al puerto que entrega Render. También ejecuta las migraciones, carga los ejemplos si aún no existen, genera las cachés y arranca Apache.»

**Opcional, si tienes tiempo:** abre `render.yaml`, líneas 1–15.

**Qué decir:**

«Este archivo deja documentada una configuración reproducible para Render: servicio web Docker, plan gratuito y entorno de producción con depuración desactivada. El servicio actual se creó con la integración de Render y estas variables se configuraron allí. La clave de aplicación se guarda como variable del entorno, fuera del repositorio.»

No afirmes que el servicio se creó importando el Blueprint: se creó mediante la integración. El archivo permite reproducir la configuración.

**Dónde:** panel de Render del servicio.

**Qué mostrar:** nombre del servicio, estado **Live**, rama `semana4-comisiones-mvc` y URL pública. No muestres la sección Environment ni los valores secretos.

**Qué decir:**

«Aquí se ve el servicio publicado y su URL. Esta demo consulta datos de ejemplo; como el plan gratuito tiene almacenamiento efímero, el arranque restaura los datos originales cuando la base no existe. Para una aplicación que permita guardar nuevas ventas habría que utilizar almacenamiento persistente.»

## 12. Cierre — 8:45 a 9:05

**Dónde:** aplicación pública; deja visibles la dirección y los resultados de junio.

**Acción:** vuelve a colocar del 1 al 30 de junio de 2025 y pulsa Calcular.

**Qué decir:**

«La demostración confirma el funcionamiento del ejercicio y su despliegue en la nube. Los modelos manejan los datos, el controlador coordina la petición, el servicio calcula las comisiones y la vista presenta la respuesta. Con esto se completa la implementación del ejercicio MVC de la semana cuatro.»

## Ruta rápida de archivos para tener abiertos

| Orden | Archivo | Qué señalar |
|---|---|---|
| 1 | routes/web.php | GET y POST, líneas 5–6 |
| 2 | app/Models/Vendedor.php | hasMany, línea 14 |
| 3 | app/Models/Venta.php | belongsTo, línea 13 |
| 4 | app/Models/Regla.php | umbral y porcentaje, línea 9 |
| 5 | database/migrations/2025_06_29_013409_create_ventas_table.php | clave foránea, fecha y monto, líneas 15–22 |
| 6 | database/seeders/VentasTableSeeder.php | ventas de junio y venta de mayo, líneas 12–22 |
| 7 | app/Http/Requests/CalcularComisionesRequest.php | reglas, líneas 10–11 |
| 8 | app/Http/Controllers/ComisionController.php | consulta y respuesta, líneas 19–27 |
| 9 | app/Services/CalculadoraComisiones.php | regla, cálculo y acumulación, líneas 9–29 |
| 10 | resources/views/comisiones/index.blade.php | formulario, errores y resultados, líneas 5–18 |
| 11 | tests/Feature/ComisionesTest.php | resultados esperados, líneas 21–29 |
| 12 | Dockerfile | entorno e inicio, líneas 1, 9 y 15 |
| 13 | docker/start.sh | preparación e inicio, líneas 16–22 |

## Respuestas breves por si el docente pregunta

**¿Por qué el servicio no rompe MVC?**
Porque es una clase auxiliar para la lógica matemática. El controlador continúa coordinando la petición, los modelos representan los datos y la vista presenta el resultado.

**¿Por qué 115 %?**
Porque el seeder original define `1.15`. Conservarlo reproduce el material recibido. Cambiarlo a `0.15` requeriría confirmar la regla y actualizar las pruebas y los resultados esperados.

**¿Por qué la venta de mayo no aparece?**
Porque `whereBetween` incluye únicamente las fechas dentro del período solicitado.

**¿Por qué usar el ID del vendedor?**
Porque identifica de forma estable el registro al acumular las comisiones.

**¿Cuál es la relación entre tablas?**
Un vendedor tiene muchas ventas y una venta pertenece a un vendedor. La clave foránea `vendedor_id` mantiene esa relación.

**¿Dónde ocurre cada cosa?**
La ruta dirige la petición; el FormRequest valida; el controlador consulta Eloquent y llama al servicio; el servicio calcula; Blade presenta la respuesta.
