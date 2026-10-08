# Protecciones y límites de la actividad

## Login conectado al CRUD

AuthController verifica el usuario y la contraseña contra SQLite. El grupo auth de routes/web.php protege el CRUD, el historial y el logout. Las comprobaciones se hacen en el servidor, por lo que escribir la URL directamente o alterar un formulario no elimina la protección.

El ID de la sesión cambia al entrar. Al salir se invalida la sesión y se renueva el token CSRF. Las páginas protegidas llevan no-store para evitar que se conserve su respuesta en la caché del navegador.

## Permisos sobre datos reales

Product.user_id apunta a users.id. Al crear un registro, user_id se toma del usuario autenticado. El campo no aparece entre los datos que puede escribir el cliente.

ProductPolicy permite ver, editar y eliminar solo cuando el usuario de la sesión es propietario. Las listas, búsquedas, contadores y el historial también se filtran por usuario. Estar autenticado no concede acceso a los datos de otra cuenta.

Los productos demo anteriores se asignan a admin mediante una migración que preserva los registros. Los productos antiguos sin propietario se deniegan por defecto.

## Contraseña MD5 exigida por el docente

El proyecto calcula MD5 antes de guardar una contraseña y utiliza el mismo método al verificar el login. No almacena la contraseña original. hash_equals realiza la comparación.

**MD5 es rápido, no tiene el coste adaptativo de bcrypt y no es adecuado para almacenar contraseñas de usuarios reales.** El límite de intentos del login ayuda frente a intentos por la web, pero no protege hashes robados frente a ataques fuera de línea. No se presenta MD5 como una garantía de seguridad.

Por eso la demostración se limita a datos de prueba y la configuración de producción exige bcrypt. Un hash MD5 no se puede convertir a bcrypt de la contraseña original sin que el usuario establezca otra contraseña o vuelva a proporcionarla.

## Control de formularios

Todos los formularios que cambian estado incluyen CSRF. ProductRequest valida datos del servidor:

- Nombre: texto obligatorio, máximo 100 caracteres.
- SKU: obligatorio, ASCII con letras/números/guiones, máximo 30; se normaliza a mayúsculas y debe ser único.
- Precio: número no negativo, hasta dos decimales y dentro del rango de la columna.
- Stock: número entero entre 0 y 1.000.000.
- Descripción: texto opcional, máximo 1.000 caracteres.
- Entradas con arrays en lugar de valores se rechazan.
- user_id u otros campos adicionales no pueden transferir la propiedad.

La validación HTML ayuda al usuario; la validación PHP sigue aplicándose aunque se omitan los controles del navegador.

## Sesiones y cookies

Las sesiones se almacenan cifradas en la base de datos mediante APP_KEY. La caducidad por inactividad es de 30 minutos. La cookie es HttpOnly, SameSite=Lax y no tiene duración persistente con SESSION_EXPIRE_ON_CLOSE=true. Algunos navegadores restauran sesiones al recuperar ventanas; la caducidad del servidor sigue siendo necesaria.

En localhost se usa HTTP y Secure=false para poder probar. En producción se requiere HTTPS y se fuerza Secure=true. No subas APP_KEY a Git.

## Intentos de acceso

Tras 5 fallos para la combinación usuario/IP o 20 fallos desde la misma IP, se bloquean nuevos intentos durante la ventana de 60 segundos. Una autenticación correcta limpia el contador de esa combinación; no borra el contador global de IP.

El mensaje de fallo no confirma si el nombre de usuario existe. No se registran contraseñas en los logs de auditoría.

## XSS, SQL y cabeceras

Blade escapa el texto mostrado. Eloquent utiliza parámetros en las consultas. CSP restringe scripts a los archivos del propio sitio y bloquea objetos y marcos externos. X-Frame-Options=DENY protege frente a incrustación en iframes. nosniff evita interpretar un archivo con otro tipo de contenido.

CSP permite estilos inline para el comportamiento de Bootstrap, pero no permite scripts inline. HSTS se envía solo cuando la solicitud llega mediante HTTPS. Si se coloca un proxy delante de Laravel, debe configurarse correctamente para identificar HTTPS y la IP del cliente.

## Auditoría y consistencia

Crear, editar o eliminar genera una fila de product_audits con usuario, fecha, acción, SKU y nombre del producto. La operación y la auditoría se ejecutan dentro de la misma transacción. Si falla la auditoría, se revierte la operación.

El historial no contiene contraseñas. Cada usuario ve solo sus propias acciones. Eliminar un producto conserva su referencia histórica. La aplicación no ofrece un endpoint para modificar ese historial.

Este registro no es inmutable frente a un administrador que tenga acceso directo a la base de datos ni guarda cada valor anterior y posterior.

## GitHub y despliegue

Git contiene el código, README, pruebas, migraciones y ejemplos de configuración. Excluye .env, SQLite, dependencias y logs. GitHub Actions está configurado para ejecutar las pruebas después de publicar el repositorio.

El proyecto es una base académica funcional. Para un servicio real aún debe definirse el entorno de alojamiento, HTTPS, backups, administración de cuentas, actualizaciones y procedimientos de recuperación. No incluye doble factor ni recuperación pública de contraseña.
