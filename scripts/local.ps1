param([switch]$Start)
$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $PSScriptRoot
$local = Join-Path $root '.local'
$wordpress = Join-Path $local 'wordpress'
$php = (Get-Command php -ErrorAction Stop).Source
$phpExtensions = Join-Path (Split-Path -Parent $php) 'ext'
$phpOptions = @('-d', "extension_dir=$phpExtensions", '-d', 'extension=pdo_sqlite', '-d', 'extension=sqlite3', '-d', 'extension=mbstring', '-d', 'extension=gd', '-d', 'extension=openssl', '-d', 'extension=curl')
$baseUrl = 'http://127.0.0.1:8079'
New-Item -ItemType Directory -Force -Path $local | Out-Null

if (!(Test-Path -LiteralPath "$wordpress/wp-load.php")) {
    $archive = Join-Path $local 'wordpress.zip'
    Invoke-WebRequest -Uri 'https://wordpress.org/latest.zip' -OutFile $archive
    tar.exe -xf $archive -C $local
    if ($LASTEXITCODE -ne 0) { throw 'Не удалось распаковать WordPress.' }
}

$plugin = "$wordpress/wp-content/plugins/sqlite-database-integration"
if (!(Test-Path -LiteralPath "$plugin/db.copy")) {
    $archive = Join-Path $local 'sqlite.zip'
    Invoke-WebRequest -Uri 'https://downloads.wordpress.org/plugin/sqlite-database-integration.latest-stable.zip' -OutFile $archive
    tar.exe -xf $archive -C "$wordpress/wp-content/plugins"
    if ($LASTEXITCODE -ne 0) { throw 'Не удалось распаковать SQLite Database Integration.' }
}

$dropIn = "$wordpress/wp-content/db.php"
if (!(Test-Path -LiteralPath $dropIn)) {
    (Get-Content -LiteralPath "$plugin/db.copy" -Raw).Replace('{SQLITE_PLUGIN}', 'sqlite-database-integration/load.php') |
        Set-Content -LiteralPath $dropIn -Encoding utf8
}

$theme = "$wordpress/wp-content/themes/medved-beauty-bar"
if (!(Test-Path -LiteralPath $theme)) {
    New-Item -ItemType Junction -Path $theme -Target "$root/theme/medved-beauty-bar" | Out-Null
}

& $php @phpOptions "$PSScriptRoot/setup.php"
if ($LASTEXITCODE -ne 0) { throw 'Не удалось подготовить WordPress.' }

if ($Start) {
    $pidFile = "$local/wordpress.pid"
    $existing = if (Test-Path -LiteralPath $pidFile) { Get-Process -Id ([int](Get-Content -LiteralPath $pidFile -Raw)) -ErrorAction SilentlyContinue }
    if (!$existing -or !(Test-NetConnection 127.0.0.1 -Port 8079 -InformationLevel Quiet -WarningAction SilentlyContinue)) {
        $options = $phpOptions + @('-S', '127.0.0.1:8079')
        $server = Start-Process -FilePath $php -ArgumentList $options -WorkingDirectory $wordpress -WindowStyle Hidden -RedirectStandardOutput "$local/wordpress.stdout.log" -RedirectStandardError "$local/wordpress.stderr.log" -PassThru
        $server.Id | Set-Content -LiteralPath $pidFile
    }
    Write-Output $baseUrl
}
