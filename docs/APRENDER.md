# Aprender lo que se construyó

## 1. Ejecutar y observar

Abre iniciar.bat y visita http://127.0.0.1:8000. Prueba entrar a /productos sin sesión, después inicia sesión con la cuenta demo. Crea un producto, consúltalo, edítalo y elimínalo.

**Idea:** CRUD significa Crear, Leer, Actualizar y Eliminar. Los datos se guardan en SQLite, aunque cierres el navegador.

## 2. Entender MVC

- **Modelo:** Product representa un registro de products; User representa un usuario.
- **Vista:** los archivos Blade generan el HTML que muestra el navegador.
- **Controlador:** recibe la petición, utiliza el modelo y devuelve la vista o una redirección.

**Ejercicio:** abre ProductController.index. Identifica la consulta a Product y la vista products.index que devuelve.

## 3. Seguir una URL

Abre routes/web.php. Route::resource genera las siete rutas del CRUD. El grupo con auth exige una sesión válida antes de ejecutar el controlador.

Ejecuta php artisan route:list --except-vendor en la copia activa para ver las rutas.

**Ejercicio:** relaciona GET /productos con index y POST /productos con store. Después abre ProductPolicy: identifica cómo compara users.id con products.user_id para denegar el acceso a registros ajenos.

## 4. Comprender el login

AuthController.create muestra el formulario. store valida username/password, limita intentos y llama a Auth::attempt. Si las credenciales son correctas, regenera la sesión y redirige al inventario. destroy cierra e invalida la sesión.

**Idea:** ocultar un botón no protege una URL. El middleware protege la petición en el servidor.

## 5. Ver la base de datos

database/migrations describe las tablas. El seeder crea datos iniciales reproducibles. El archivo SQLite activo está en AppData cuando utilizas iniciar.ps1.

**Idea:** una migración es una receta para construir o modificar una tabla. El modelo es la clase que usa la tabla.

**Ejercicio:** identifica qué migración establece que sku debe ser único.

## 6. Entender las contraseñas

El seeder utiliza Hash::make con el driver MD5 registrado en AppServiceProvider. Auth::attempt compara el hash de la contraseña ingresada con el almacenado mediante Md5Hasher. MD5 se usa por exigencia del docente; para usuarios reales se requiere un algoritmo apropiado como bcrypt. No es cifrado reversible.

Ejecuta php artisan demo:password y observa el algoritmo.

## 7. Comprender la validación

ProductRequest contiene las reglas. Además, comprueba que tengas permiso para actualizar el producto. El navegador ayuda con required y min, pero Laravel valida de nuevo en el servidor. Solo se guardan los campos de validated().

**Ejercicio:** intenta registrar un SKU existente o un stock negativo y observa el error.

## 8. Leer las vistas y Bootstrap

layouts/app.blade.php contiene el marco común. create y edit reutilizan products/form.blade.php. Los archivos de Bootstrap están en public/vendor/bootstrap.

**Ejercicio:** encuentra las clases row, col-md-6, card, table y btn. Después cambia un texto de una vista y reinicia iniciar.ps1 para sincronizar el código.

## 9. Comprender CSRF y los métodos HTTP

@csrf genera un token que comprueba Laravel. @method('PUT') y @method('DELETE') permiten que un formulario HTML represente actualizar o eliminar. Las acciones que modifican datos no se ejecutan mediante GET.

## 10. Auditoría, pruebas y Git

Abre ProductController.recordAudit y sigue una creación. La transacción guarda el producto y ProductAudit juntos. La pantalla /actividad muestra las operaciones de tu cuenta.

php artisan test --compact comprueba el comportamiento con una base de datos separada en memoria. SecurityControlsTest intenta acceder a productos de otra cuenta para comprobar el bloqueo, no solo la apariencia de la página.

git status muestra cambios. git add y git commit guardan una versión del código. .gitignore excluye archivos privados y archivos que se regeneran.

**Para explicar al docente:** toma una acción como crear un producto y cuenta su recorrido: formulario → ruta protegida → controlador → validación → modelo → base de datos → redirección → vista.
