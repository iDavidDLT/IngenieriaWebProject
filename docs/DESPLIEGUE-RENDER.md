# Desplegar en Render

## Despliegue completado

La aplicación ya está publicada y verificada: https://comisiones-mvc-semana4.onrender.com/comisiones

Panel del servicio: https://dashboard.render.com/web/srv-db6ln4c9v7es7380pn6g

No hace falta crear otro servicio ni introducir claves para usar el despliegue actual. Los pasos siguientes sirven para reproducirlo en otro servicio.

## Fuente

Repositorio: https://github.com/iDavidDLT/IngenieriaWebProject
Rama: `semana4-comisiones-mvc` (ejercicio aislado; no modifica main).

## Método recomendado: Blueprint

1. Inicia sesión en https://dashboard.render.com con GitHub.
2. Abre **New → Blueprint** y conecta `IngenieriaWebProject`. Si no aparece, concede acceso al repositorio mediante la conexión GitHub de Render.
3. Selecciona la rama **semana4-comisiones-mvc**, donde está `render.yaml`. Si Render indica que no encuentra el Blueprint, verifica la rama.
4. Render leerá un único servicio Docker con plan **Free**. Mantén ese plan.
5. Para `APP_KEY`, genera una clave distinta de la local y colócala en el campo secreto de Render. En la carpeta del proyecto ejecuta:

```powershell
& '../../.tools/php8425/php.exe' artisan key:generate --show
```

En otro equipo: `php artisan key:generate --show`. Copia la salida completa `base64:...` en Render. No publiques esta clave en GitHub ni la muestres en la grabación.

6. Pulsa **Deploy Blueprint** y espera que el servicio muestre **Live**.
7. Abre el dominio HTTPS real que Render entregue, normalmente `https://comisiones-mvc-semana4-....onrender.com`. La raíz redirige a `/comisiones`.
8. Comprueba `/up`, calcula junio de 2025 y verifica $4.400 de ventas y $1.634 de comisiones.

## Alternativa: New → Web Service

Conecta el mismo repositorio y configura:

- Branch: `semana4-comisiones-mvc`.
- Language/Runtime: **Docker**.
- Root Directory: vacío (la rama contiene el proyecto en la raíz).
- Dockerfile Path: `./Dockerfile`.
- Instance Type: **Free**.
- Health Check Path: `/up`.

Añade las variables del archivo `render.yaml`; `APP_KEY` debe generarse aparte como se explicó. Es especialmente necesario `APP_ENV=production`, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true` y `LOG_CHANNEL=stderr`. `docker/start.sh` configura SQLite e interpreta el puerto `PORT` de Render. No hace falta crear un PostgreSQL ni añadir comandos de build y start adicionales.

## Qué ejecuta el contenedor

Instala extensiones PHP y Composer, sirve exclusivamente `public/` con Apache, ejecuta migraciones, carga ejemplos si las tablas están vacías y crea cachés Laravel. Las peticiones HTTPS del proxy de Render se reconocen mediante cabeceras reenviadas, para que los formularios y estilos usen HTTPS.

Render Free apaga el servicio tras 15 minutos sin tráfico y el primer acceso puede tardar alrededor de un minuto. Abre la aplicación antes de empezar a grabar. Su disco es efímero: esta demo no ofrece edición de datos y restaura automáticamente los datos originales al iniciar. No es una base para guardar nuevos registros de producción.

Referencias oficiales:
- Docker/PHP: https://render.com/docs/docker
- Plan gratuito y disco efímero: https://render.com/docs/free
- Blueprint: https://render.com/docs/blueprint-spec

## Estado de verificación

Consulta `docs/VERIFICACION.md` para ver lo que se comprobó efectivamente. La existencia de Dockerfile y render.yaml no prueba que esté publicado; solo se considera completado cuando existe una URL pública que responde y calcula correctamente.
