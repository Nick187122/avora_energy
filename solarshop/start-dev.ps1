$ErrorActionPreference = "Stop"
$root = Split-Path -Parent $MyInvocation.MyCommand.Path
$mysqlBin = "C:\xampp\mysql\bin\mysqld.exe"
$myIni = "C:\xampp\mysql\bin\my.ini"

$mysqlRunning = Get-NetTCPConnection -LocalPort 3306 -State Listen -ErrorAction SilentlyContinue
if (-not $mysqlRunning) {
    Write-Host "Starting MySQL (XAMPP)..."
    Start-Process -FilePath $mysqlBin -ArgumentList "--defaults-file=$myIni" -WindowStyle Hidden
    $up = $false
    for ($i = 0; $i -lt 30; $i++) {
        Start-Sleep -Seconds 1
        if (Get-NetTCPConnection -LocalPort 3306 -State Listen -ErrorAction SilentlyContinue) { $up = $true; break }
    }
    if (-not $up) { throw "MySQL did not start on port 3306. Check C:\xampp\mysql\data\*.err" }
    Write-Host "MySQL is up."
} else {
    Write-Host "MySQL already running."
}

$serving = Get-NetTCPConnection -LocalPort 8000 -State Listen -ErrorAction SilentlyContinue
if (-not $serving) {
    Write-Host "Starting Laravel dev server on http://localhost:8000 ..."
    Start-Process -FilePath "php" -ArgumentList "artisan serve --host=127.0.0.1 --port=8000" -WorkingDirectory $root -WindowStyle Hidden
    Start-Sleep -Seconds 3
}
Write-Host ""
Write-Host "Storefront: http://localhost:8000"
Write-Host "Admin:      http://localhost:8000/admin/login  (admin@solarshop.co.ke / admin12345)"
Write-Host ""