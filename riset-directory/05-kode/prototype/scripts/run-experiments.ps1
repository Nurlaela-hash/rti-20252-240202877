<#
PowerShell runner: jalankan benchmark 10 kali dan simpan output ke ../..\..\06-output\experiment-logs\
Prasyarat: jalankan dari folder riset-directory/05-kode/prototype
#>

param(
    [int]$Runs = 10,
    [switch]$AutoConfirm
)

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Definition
$prototypeDir = (Resolve-Path (Join-Path $scriptDir '..')).Path
Set-Location $prototypeDir

$root = $prototypeDir
$logsDir = Join-Path $root "riset-directory\06-output\experiment-logs"
$dataDir = Join-Path $root "riset-directory\04-data"
if (-Not (Test-Path $logsDir)) { New-Item -ItemType Directory -Path $logsDir -Force | Out-Null }
if (-Not (Test-Path $dataDir)) { New-Item -ItemType Directory -Path $dataDir -Force | Out-Null }

Write-Host "Starting experiments: $Runs runs. Logs -> $logsDir"

# Pastikan dataset sudah di-generate
Write-Host "Ensure dataset is generated (npm run seed) before running this script."

if (-not $AutoConfirm) {
    Write-Host "Please start both services locally (Express and Laravel) in separate terminals now, then press Enter to continue."
    Read-Host -Prompt "Press Enter after services are started"
} else {
    Write-Host "AutoConfirm enabled: proceeding without interactive prompt. Assuming services are already started."
}

$k6Exe = 'C:\tools\k6\k6-v0.54.0-windows-amd64\k6.exe'
if (-not (Test-Path $k6Exe)) { $k6Exe = 'k6' }

$mysqldumpExe = 'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe'
$seedSqlPath = Join-Path $dataDir 'seed.sql'
if (Test-Path $mysqldumpExe) {
    Write-Host "Exporting seed SQL to $seedSqlPath"
    & $mysqldumpExe --host=127.0.0.1 --port=3306 --user=benchmark --password=benchmark --databases benchmark_rti --result-file=$seedSqlPath | Out-Null
} else {
    Write-Warning "mysqldump.exe was not found; skipping SQL export."
}

$env:K6_BASE_URL = 'http://localhost:3000'
$env:TARGET_FRAMEWORK = 'express'
$env:WARMUP_SECONDS = '30'
$env:RAMP_UP_SECONDS = '10'
$env:SUSTAIN_SECONDS = '20'
$env:CONCURRENT_USERS = '20'

for ($i = 1; $i -le $Runs; $i++) {
    $runLabel = "run-{0:D2}" -f $i
    $outFile = Join-Path $logsDir "$runLabel.txt"
    Write-Host "Running experiment $i -> $outFile"

    $timestamp = Get-Date -Format o
    Add-Content -Path $outFile -Value "Run: $i | Started: $timestamp`n"

    try {
        & $k6Exe run benchmark/k6/complex-crud.js 2>&1 | Tee-Object -FilePath $outFile -Append
    } catch {
        Write-Error "Benchmark failed on run ${i}: $($_.Exception.Message)"
        Add-Content -Path $outFile -Value "ERROR: $($_.Exception.Message)`n"
    }

    $timestampEnd = Get-Date -Format o
    Add-Content -Path $outFile -Value "Finished: $timestampEnd`n---`n"

    Start-Sleep -Seconds 5
}

Write-Host "All runs completed. Logs are in $logsDir"
