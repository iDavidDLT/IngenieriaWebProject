# Guion del video — máximo 3 minutos

Prepara el servidor y una pestaña privada del navegador antes de grabar. Ten abierta una terminal en la carpeta activa con el comando para mostrar el hash. Graba pantalla y voz con Loom o la herramienta que utilices para subir el video a YouTube.

| Tiempo | Acción en pantalla | Explicación sugerida |
| --- | --- | --- |
| 0:00–0:15 | Mostrar el login | “Esta aplicación de Ingeniería Web utiliza Laravel, Bootstrap y SQLite con el patrón MVC.” |
| 0:15–0:35 | Escribir directamente /productos y luego /productos/create sin sesión | “Las rutas del inventario están protegidas. Sin iniciar sesión, el middleware me devuelve al login.” |
| 0:35–0:50 | Intentar una contraseña incorrecta | “Las credenciales incorrectas no permiten entrar.” |
| 0:50–1:05 | Iniciar sesión como admin | “Al ingresar las credenciales correctas, accedo a la sección protegida.” |
| 1:05–1:30 | Crear “Producto de video”, SKU VIDEO-001, precio 12.50, cantidad 10 | “Aquí realizo Crear; Laravel valida los datos y los guarda en la base de datos.” |
| 1:30–1:45 | Ver listado y detalle | “Esta es la operación Leer.” |
| 1:45–2:00 | Editar cantidad a 15 y guardar | “Esta es la operación Actualizar; el nuevo valor queda guardado.” |
| 2:00–2:15 | Eliminar con confirmación | “Esta es la operación Eliminar.” |
| 2:15–2:40 | Mostrar la terminal con demo:password; señalar Hash::make en el seeder | “La contraseña se guarda mediante bcrypt. La base de datos almacena este hash, no la contraseña original. Bcrypt es un hash apropiado para contraseñas; utilizo este método en lugar del ejemplo MD5.” |
| 2:40–2:55 | Cerrar sesión e ingresar otra vez /productos | “Después del logout, ya no puedo entrar al inventario sin volver a iniciar sesión.” |

Duración objetivo: **2 minutos 55 segundos**. Ensaya una vez y evita detenerte a explicar cada archivo en el video.

## Preparar la evidencia del hash en este equipo

Abre una segunda terminal de PowerShell:

```powershell
$php = "C:\Users\ASUS\Documents\Ingenieria Web\.tools\php8425\php.exe"
Set-Location "$env:LOCALAPPDATA\IngenieriaWeb\inventario-mvc"
& $php artisan demo:password
```

En una instalación con PHP en PATH utiliza `php artisan demo:password`.

## Antes de entregar

- Comprueba que el video dure como máximo 3 minutos.
- Comprueba que el enlace permita al docente reproducirlo.
- Usa “No listado” en YouTube si no deseas que aparezca públicamente en búsquedas.
- Añade el enlace en el README.
- Verifica que el repositorio remoto esté accesible para el docente.
- Si VIDEO-001 ya existe de un ensayo, elimínalo antes de repetir la grabación.
