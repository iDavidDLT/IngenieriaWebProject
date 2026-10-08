param([switch]$SoloRuta, [switch]$ActualizarRuta)

$ErrorActionPreference = 'Stop'
$runtimeRoot = Join-Path $env:LOCALAPPDATA 'IngenieriaWeb/inventario-mvc'
$portablePhp = Join-Path (Split-Path $PSScriptRoot -Parent) '.tools/php8425/php.exe'
if (Test-Path -LiteralPath $portablePhp) {
    $phpExecutable = $portablePhp
} elseif (Get-Command php -ErrorAction SilentlyContinue) {
    $phpExecutable = (Get-Command php).Source
} else {
    throw 'No se encuentra PHP. Consulta el README.'
}
$savedPathFile = Join-Path $PSScriptRoot 'database/base-activa.txt'
if ((Test-Path -LiteralPath $savedPathFile) -and -not $ActualizarRuta) {
    $databasePath = [System.IO.File]::ReadAllText($savedPathFile).Trim()
} else {
    if (-not (Test-Path -LiteralPath (Join-Path $runtimeRoot 'artisan'))) {
        throw 'Inicia primero la aplicacion con iniciar.bat.'
    }
    Push-Location $runtimeRoot
    try {
        $databasePath = & $phpExecutable artisan db:archivo --solo-ruta --no-ansi
        if ($LASTEXITCODE -ne 0) { throw 'No se pudo consultar la base de datos activa.' }
        $databasePath = ($databasePath | Out-String).Trim()
    } finally { Pop-Location }
}

# Resolver el archivo fisico tambien cuando Windows redirige AppData.
if (-not ('InventarioFilePath' -as [type])) {
    Add-Type -TypeDefinition @'
using System;
using System.Runtime.InteropServices;
using System.Text;
using Microsoft.Win32.SafeHandles;
public static class InventarioFilePath {
    [DllImport("kernel32.dll", CharSet = CharSet.Unicode, SetLastError = true)]
    public static extern uint GetFinalPathNameByHandle(SafeFileHandle handle, StringBuilder path, uint size, uint flags);
}
'@
}
$fileStream = [System.IO.File]::Open($databasePath, [System.IO.FileMode]::Open, [System.IO.FileAccess]::Read, ([System.IO.FileShare]::ReadWrite -bor [System.IO.FileShare]::Delete))
try {
    $pathBuffer = New-Object System.Text.StringBuilder 4096
    $pathLength = [InventarioFilePath]::GetFinalPathNameByHandle($fileStream.SafeFileHandle, $pathBuffer, 4096, 0)
    if ($pathLength -eq 0 -or $pathLength -ge 4096) { throw 'No se pudo resolver la ruta fisica de SQLite.' }
    $physicalPath = $pathBuffer.ToString()
    if ($physicalPath.StartsWith('\\?\UNC\')) { $physicalPath = '\\' + $physicalPath.Substring(8) }
    elseif ($physicalPath.StartsWith('\\?\')) { $physicalPath = $physicalPath.Substring(4) }
} finally { $fileStream.Dispose() }

if ($ActualizarRuta) {
    try {
        [System.IO.File]::WriteAllText($savedPathFile, $physicalPath, [System.Text.UTF8Encoding]::new($false))
    } catch {
        Write-Warning 'Windows no permite guardar la referencia en Documentos. Abre en DB Browser la ruta que imprime el iniciador.'
    }
}

if ($SoloRuta) {
    Write-Output $physicalPath
    exit 0
}
Write-Host ('Base de datos activa: ' + $physicalPath)
Write-Host 'En DB Browser, refresca Examinar datos despues de guardar desde la web.'
Write-Host 'Cierra la base antigua de Documentos y evita dejar cambios SQL pendientes.'
$viewerCandidates = @(
    (Join-Path $env:ProgramFiles 'DB Browser for SQLite/DB Browser for SQLite.exe'),
    (Join-Path ${env:ProgramFiles(x86)} 'DB Browser for SQLite/DB Browser for SQLite.exe')
)
$dbViewer = $viewerCandidates | Where-Object { Test-Path -LiteralPath $_ } | Select-Object -First 1
if ($dbViewer) {
    Start-Process -FilePath $dbViewer -ArgumentList ('"{0}"' -f $physicalPath) -WindowStyle Normal
} else {
    Write-Host 'Instala DB Browser for SQLite o abre manualmente el archivo indicado.'
}
