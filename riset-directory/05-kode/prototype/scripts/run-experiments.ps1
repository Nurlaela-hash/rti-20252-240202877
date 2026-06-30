<#
PowerShell runner: jalankan benchmark 10 kali dan simpan output ke ../..\..\06-output\experiment-logs\
Prasyarat: jalankan dari folder riset-directory/05-kode/prototype
#>

param(
    [int]$Runs = 10,
    [ValidateSet("express", "laravel")][string]$Framework = "express",
    [switch]$AutoConfirm
)

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Definition
$prototypeDir = (Resolve-Path (Join-Path $scriptDir '..')).Path
Set-Location $prototypeDir

# Workspace root is 3 levels up from prototype directory
$workspaceRoot = (Resolve-Path (Join-Path $prototypeDir "..\..\..")).Path
$logsDir = Join-Path $workspaceRoot "riset-directory\06-output\experiment-logs"
$dataDir = Join-Path $workspaceRoot "riset-directory\04-data"
if (-Not (Test-Path $logsDir)) { New-Item -ItemType Directory -Path $logsDir -Force | Out-Null }
if (-Not (Test-Path $dataDir)) { New-Item -ItemType Directory -Path $dataDir -Force | Out-Null }

Write-Host "Starting experiments: $Runs runs for $Framework. Logs -> $logsDir"

# Pastikan container sudah berjalan
if (-not $AutoConfirm) {
    Write-Host "Please make sure Docker containers are running (docker compose up -d) and seeded (docker compose run --rm db-seed) before continuing."
    Read-Host -Prompt "Press Enter to continue"
} else {
    Write-Host "AutoConfirm enabled: proceeding without interactive prompt. Assuming Docker environment is ready."
}

# Export seed database ke riset-directory/04-data/seed.sql
$seedSqlPath = Join-Path $dataDir 'seed.sql'
Write-Host "Exporting seed SQL to $seedSqlPath using docker compose..."
try {
    docker compose exec -T mysql mysqldump --host=127.0.0.1 --port=3306 --user=benchmark --password=benchmark --databases benchmark_rti > $seedSqlPath
    Write-Host "Database seed exported successfully to $seedSqlPath"
} catch {
    Write-Warning "Failed to export database seed: $_"
}

# Setup environment variables untuk k6
$env:WARMUP_SECONDS = '30'
$env:RAMP_UP_SECONDS = '10'
$env:SUSTAIN_SECONDS = '20'
$env:CONCURRENT_USERS = '20'

if ($Framework -eq "express") {
    $env:K6_BASE_URL = 'http://express:3000'
    $env:TARGET_FRAMEWORK = 'express'
} else {
    $env:K6_BASE_URL = 'http://laravel:8000'
    $env:TARGET_FRAMEWORK = 'laravel'
}

for ($i = 1; $i -le $Runs; $i++) {
    $runLabel = "run-$Framework-{0:D2}" -f $i
    $outFile = Join-Path $logsDir "$runLabel.txt"
    Write-Host "Running experiment $i for $Framework -> $outFile"

    $timestamp = Get-Date -Format o
    Add-Content -Path $outFile -Value "Run: $i | Started: $timestamp | Framework: $Framework`n"

    try {
        # Jalankan k6 via Docker Compose
        docker compose run --rm `
            -e K6_BASE_URL=$env:K6_BASE_URL `
            -e TARGET_FRAMEWORK=$env:TARGET_FRAMEWORK `
            -e WARMUP_SECONDS=$env:WARMUP_SECONDS `
            -e RAMP_UP_SECONDS=$env:RAMP_UP_SECONDS `
            -e SUSTAIN_SECONDS=$env:SUSTAIN_SECONDS `
            -e CONCURRENT_USERS=$env:CONCURRENT_USERS `
            k6 2>&1 | Tee-Object -FilePath $outFile -Append
    } catch {
        Write-Error "Benchmark failed on run ${i}: $($_.Exception.Message)"
        Add-Content -Path $outFile -Value "ERROR: $($_.Exception.Message)`n"
    }

    $timestampEnd = Get-Date -Format o
    Add-Content -Path $outFile -Value "Finished: $timestampEnd`n---`n"

    Start-Sleep -Seconds 5
}

Write-Host "All $Runs runs completed for $Framework. Logs are in $logsDir"
