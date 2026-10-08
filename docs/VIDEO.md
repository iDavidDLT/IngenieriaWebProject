# Video académico — máximo 3 minutos

Prepara el servidor, una ventana privada del navegador y una segunda terminal con el comando demo:password.

| Tiempo | Acción | Explicación sugerida |
| --- | --- | --- |
| 0:00–0:15 | Mostrar el login | “Aplicación de Ingeniería Web con Laravel, Bootstrap, SQLite y MVC.” |
| 0:15–0:35 | Escribir /productos y /productos/create sin sesión | “Las URLs están protegidas por auth: sin login vuelvo al formulario.” |
| 0:35–0:50 | Usar contraseña incorrecta | “Las credenciales incorrectas no permiten entrar.” |
| 0:50–1:05 | Login como admin | “Con el usuario y contraseña correctos accedo al CRUD.” |
| 1:05–1:30 | Crear Producto de video, VIDEO-001, precio 12.50 y cantidad 10 | “Los datos se validan en el servidor y el producto queda vinculado a mi usuario.” |
| 1:30–1:45 | Abrir listado y detalle | “Esta es la operación Leer.” |
| 1:45–2:00 | Cambiar cantidad a 15 y guardar | “Esta es la operación Actualizar.” |
| 2:00–2:15 | Eliminar con confirmación | “Esta es la operación Eliminar.” |
| 2:15–2:25 | Abrir Historial | “Se registra quién creó, editó o eliminó el producto y cuándo.” |
| 2:25–2:40 | Mostrar demo:password | “La base de datos guarda este hash MD5 de 32 caracteres, como exige la actividad, no la contraseña original. MD5 se usa aquí para la demostración académica.” |
| 2:40–2:55 | Logout e intentar /productos | “Después de salir no puedo entrar al CRUD sin volver a autenticarme.” |

Duración objetivo: **2:55**. Evita explicar todos los archivos en el video.

## Mostrar MD5 en este equipo

```powershell
$php = "C:\Users\ASUS\Documents\Ingenieria Web\.tools\php8425\php.exe"
Set-Location "$env:LOCALAPPDATA\IngenieriaWeb\inventario-mvc"
& $php artisan demo:password
```

Con PHP en PATH: php artisan demo:password.

## Entregar

Graba y publica en Loom o YouTube. Comprueba que el docente pueda abrir el enlace y que la duración sea menor o igual a 3 minutos. Agrega el enlace al README. Completa tu nombre y la URL del repositorio.

Si VIDEO-001 existe de un ensayo anterior, elimínalo antes de grabar. Los registros del historial de los ensayos permanecen como evidencia de las operaciones.
