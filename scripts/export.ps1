$ErrorActionPreference = 'Stop'
$php = (Get-Command php -ErrorAction Stop).Source
$phpExtensions = Join-Path (Split-Path -Parent $php) 'ext'
& $php -d "extension_dir=$phpExtensions" -d extension=pdo_sqlite -d extension=sqlite3 -d extension=mbstring "$PSScriptRoot/export.php"
if ($LASTEXITCODE -ne 0) { throw 'Экспорт WordPress завершился ошибкой.' }
