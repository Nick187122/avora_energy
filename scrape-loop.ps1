$ErrorActionPreference = "Continue"
Set-Location "C:\solarshop-scrape"
$workdir = "C:\solarshop-scrape"
$log = Join-Path $workdir "scrape-loop.log"
$maxBatches = 20
$batchSize = 30

for ($i = 1; $i -le $maxBatches; $i++) {
    Add-Content -Path $log -Value "[$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')] === Batch $i ==="
    $out = & firecrawl x download https://solarshop.co.ke --limit $batchSize -y 2>&1
    Add-Content -Path $log -Value $out
    Add-Content -Path $log -Value "[$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')] Batch $i done. Sleeping 90s."
    Start-Sleep -Seconds 90
}
