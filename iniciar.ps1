$ErrorActionPreference = 'Stop'
$projectSource = $PSScriptRoot
$runtime = Join-Path $env:LOCALAPPDATA 'IngenieriaWeb/inventario-mvc'
$portablePhp = Join-Path (Split-Path $projectSource -Parent) '.tools/php8425/php.exe'
if (Test-Path -LiteralPath $portablePhp) {
    $php = $portablePhp
} elseif (Get-Command php -ErrorAction SilentlyContinue) {
    $php = (Get-Command php).Source
} else {
    throw 'No se encontró PHP. Instala PHP 8.4 y Composer siguiendo el README.'
}
New-Item -ItemType Directory -Path $runtime -Force | Out-Null
& robocopy $projectSource $runtime /E /XD .git storage node_modules /XF .env '*.sqlite' /NFL /NDL /NJH /NJS /NP
if ($LASTEXITCODE -gt 7) { throw 'No se pudo sincronizar el proyecto con AppData.' }
foreach ($folder in @('storage/logs', 'storage/framework/cache/data', 'storage/framework/sessions', 'storage/framework/views', 'storage/app/private', 'storage/app/public', 'bootstrap/cache')) {
    New-Item -ItemType Directory -Path (Join-Path $runtime $folder) -Force | Out-Null
}
if (-not (Test-Path (Join-Path $runtime 'vendor/autoload.php'))) { throw 'Faltan las dependencias. Ejecuta composer install siguiendo el README.' }
if (-not (Test-Path (Join-Path $runtime '.env'))) {
    Copy-Item -LiteralPath (Join-Path $projectSource '.env.example') -Destination (Join-Path $runtime '.env')
}
if (-not (Test-Path (Join-Path $runtime 'database/database.sqlite'))) {
    New-Item -ItemType File -Path (Join-Path $runtime 'database/database.sqlite') | Out-Null
}
$env:PATH = (Split-Path $php -Parent) + ';' + $env:PATH
Push-Location $runtime
try {
    if (-not (Select-String -LiteralPath '.env' -Pattern '^APP_KEY=base64:' -Quiet)) {
        & $php artisan key:generate --no-interaction
        if ($LASTEXITCODE -ne 0) { throw 'No se pudo generar APP_KEY.' }
    }
    & $php artisan migrate --seed --force --no-interaction
    if ($LASTEXITCODE -ne 0) { throw 'No se pudo preparar la base de datos.' }
    Write-Host 'Aplicación: http://127.0.0.1:8000'
    Write-Host 'Usuario: admin | Contraseña: IngenieriaWeb2026!'
    Write-Host ('Base de datos persistente: ' + (Join-Path $runtime 'database/database.sqlite'))
    Write-Host 'Presiona Ctrl+C para detener el servidor.'
    & $php artisan serve --host=127.0.0.1 --port=8000 --no-reload
} finally { Pop-Location }
