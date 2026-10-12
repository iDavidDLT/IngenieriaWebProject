$ErrorActionPreference = 'Stop'
Set-Location -LiteralPath $PSScriptRoot
$portablePhp = Join-Path $PSScriptRoot '../../.tools/php8425/php.exe'
if (Test-Path -LiteralPath $portablePhp) { $mvcPhp = (Resolve-Path $portablePhp).Path }
elseif (Get-Command php -ErrorAction SilentlyContinue) { $mvcPhp = (Get-Command php).Source }
else { throw 'No se encontró PHP 8.2 o posterior.' }
if (-not (Test-Path 'vendor/autoload.php')) { throw 'Ejecuta composer install antes de iniciar.' }
if (-not (Test-Path '.env')) { Copy-Item '.env.example' '.env' }
if (-not (Test-Path 'database/database.sqlite')) { New-Item -ItemType File 'database/database.sqlite' | Out-Null }
if (-not (Select-String '.env' -Pattern '^APP_KEY=base64:' -Quiet)) {
    & $mvcPhp artisan key:generate --no-interaction
    if ($LASTEXITCODE -ne 0) { throw 'No se pudo crear APP_KEY.' }
}
& $mvcPhp artisan migrate --force --no-interaction
if ($LASTEXITCODE -ne 0) { throw 'Falló la migración.' }
& $mvcPhp artisan demo:preparar --no-interaction
if ($LASTEXITCODE -ne 0) { throw 'No se pudieron cargar los datos.' }
Write-Host 'Aplicación: http://127.0.0.1:8014/comisiones'
& $mvcPhp artisan serve --host=127.0.0.1 --port=8014 --no-reload
