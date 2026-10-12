# Estado de verificación

Fecha: 12 de octubre de 2026 (Ecuador).

## Comprobaciones locales completadas

- PHP 8.4.25 y Laravel 12.69.3.
- Migraciones y seeders del ejercicio ejecutados con SQLite.
- 12 pruebas aprobadas, 43 comprobaciones (`artisan test --compact`).
- Auditoría Composer de dependencias de producción: sin avisos conocidos tras actualizar el lockfile dentro de Laravel 12 y conservar compatibilidad PHP 8.2.
- Navegador Edge de prueba, escritorio 1440 × 1100 y móvil 390 × 844.
- Cálculo de junio: seis ventas, $4,400.00 y $1,634.00 de comisiones.
- Validación de fechas invertidas, período vacío, ausencia de errores JavaScript y ausencia de desbordamiento de la página móvil. Las tablas anchas tienen desplazamiento horizontal dentro de su panel.
- Creación de cachés de rutas y vistas.
- Arranque repetido de datos de demostración sin duplicarlos.

## Despliegue

Servicio creado en Render mediante su integración, plan Free, región Virginia, runtime Docker.

- Repositorio: https://github.com/iDavidDLT/IngenieriaWebProject/tree/semana4-comisiones-mvc
- Código construido: e5a9cf6666a0bcb0edffd0c662da15932267121b
- Servicio: srv-db6ln4c9v7es7380pn6g
- Despliegue: dep-db6ln4s9v7es7380ppag
- URL asignada: https://comisiones-mvc-semana4.onrender.com

Despliegue confirmado **Live** por Render. Comprobación con navegador contra HTTPS completada: /up responde 200, el formulario POST calcula junio correctamente, se muestran las validaciones de fechas y el período vacío, y la vista móvil no desborda la página. No hay errores JavaScript. Docker se construyó y ejecutó correctamente en Render; no se ejecutó localmente porque ese runtime no está instalado en el equipo.
