# Guion del video de Ingeniería Web — duración objetivo 2:55

## Preparar antes de grabar

1. Inicia la aplicación y abre una ventana privada en http://127.0.0.1:8000/login. Todavía no inicies sesión.
2. Cierra en DB Browser la copia antigua de database/database.sqlite de Documentos. Abre **ver-base-datos.bat**, que utiliza la ruta física de la base activa, también si Windows redirige AppData.
3. En DB Browser, deja preparada esta consulta en Execute SQL / Ejecutar SQL:

    SELECT name, sku, price, stock
    FROM products
    WHERE sku = 'VIDEO-001';

4. Prepara una segunda PowerShell con el resultado de **demo:password**. Muestra únicamente el hash de la cuenta demo y su algoritmo.
5. Deja el repositorio GitHub abierto en otra pestaña.
6. Utiliza un SKU nuevo o elimina tu propio producto VIDEO-001 de un ensayo anterior antes de comenzar. No borres tablas.
7. Ensaya una vez el cambio entre navegador, DB Browser y terminal. Desactiva notificaciones para evitar interrupciones.

Utiliza la cuenta de demostración que ya tienes preparada. Sus credenciales no se muestran como ayuda en la pantalla del login. No escribas contraseñas en el guion público ni utilices credenciales de cuentas reales para el ensayo.

## Acciones y narración

| Tiempo | Qué mostrar | Qué decir |
| --- | --- | --- |
| 0:00–0:10 | Pantalla de login | “Esta aplicación de Ingeniería Web utiliza Laravel, Bootstrap y SQLite, con el patrón MVC y un inventario inspirado visualmente en Ideal Alambrec.” |
| 0:10–0:25 | Escribe /productos y después /productos/create sin sesión | “Estas URLs están protegidas. Aunque escriba la dirección directamente, el servidor me devuelve al login porque no estoy autenticado.” |
| 0:25–0:45 | Intenta una contraseña incorrecta y después ingresa correctamente | “Las credenciales incorrectas son rechazadas. Con un usuario y contraseña válidos puedo acceder al inventario.” |
| 0:45–1:10 | Crea Alambre galvanizado, SKU VIDEO-001, precio 12.50, stock 10 | “Esta es la operación Crear. Los datos se validan en el servidor y el producto queda relacionado con mi usuario.” |
| 1:10–1:20 | Abre el listado y el detalle | “Esta es la operación Leer: puedo consultar el producto, su precio y sus existencias.” |
| 1:20–1:35 | Edita stock de 10 a 15 y guarda | “Esta es la operación Actualizar. Cambio las existencias y guardo la modificación.” |
| 1:35–1:55 | Cambia a DB Browser y ejecuta de nuevo la consulta preparada | “La tabla products muestra VIDEO-001 con stock 15. El cambio está guardado en la base de datos activa, no solo en la pantalla.” |
| 1:55–2:10 | Muestra la terminal con demo:password | “La contraseña se almacena como un hash MD5 de 32 caracteres, como exige la actividad. MD5 se utiliza para esta demostración académica.” |
| 2:10–2:25 | Vuelve al navegador, elimina el producto y confirma | “Esta es la operación Eliminar. La aplicación pide confirmación antes de retirar el producto del inventario.” |
| 2:25–2:35 | Abre Historial | “El historial conserva las operaciones de creación, actualización y eliminación, junto con el usuario y la fecha.” |
| 2:35–2:50 | Cierra sesión e intenta /productos otra vez | “Después de cerrar sesión, la URL vuelve a estar bloqueada y necesito autenticarme de nuevo.” |
| 2:50–2:55 | Muestra brevemente GitHub | “El repositorio incluye el código MVC, las migraciones, las pruebas y el README.” |

No expliques todos los archivos en el video: la defensa del código se prepara aparte. Utiliza datos ficticios; no muestres claves privadas, cookies o archivos .env.

## Terminal para la evidencia MD5

    $php = "C:\Users\ASUS\Documents\Ingenieria Web\.tools\php8425\php.exe"
    Set-Location "$env:LOCALAPPDATA\IngenieriaWeb\inventario-mvc"
    & $php artisan demo:password

Si estás ejecutando el proyecto fuera del entorno que lo preparó y Windows tiene varias copias, utiliza la carpeta situada dos niveles por encima del archivo SQLite que muestra ver-base-datos.bat: la carpeta inventario-mvc que contiene artisan.

Con PHP en PATH: php artisan demo:password.

MD5 es un hash, no cifrado reversible. No es adecuado para contraseñas reales; se utiliza aquí por el requisito académico.

## Si la tabla parece no cambiar

- Comprueba que DB Browser abrió la ruta física de la base activa. La copia inicial de Documentos no es la utilizada por el servidor iniciado con iniciar.ps1.
- Después de guardar en la página, refresca Examinar datos o vuelve a ejecutar SELECT.
- No dejes modificaciones o transacciones pendientes en DB Browser: pueden bloquear SQLite.
- Si utilizas otra cuenta, comprueba el propietario: los inventarios están separados por usuario.

## Entrega

Repositorio: https://github.com/iDavidDLT/IngenieriaWebProject

Graba en Loom o publica en YouTube. Comprueba que el enlace sea accesible para el profesor y que la duración no supere tres minutos. Añade el enlace al README y completa tu nombre.

Los registros de auditoría de ensayos permanecen en el historial aunque elimines el producto de prueba.
