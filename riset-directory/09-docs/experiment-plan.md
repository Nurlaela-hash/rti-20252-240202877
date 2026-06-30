# Rencana Eksperimen: Perbandingan Laravel vs Express.js

Tujuan: Menjalankan benchmark komparatif sesuai proposal untuk mengukur perbedaan performa antara Laravel 11 dan Express.js 4.x.

Ringkasan protokol
- Dataset: 100.000 baris (deterministik) — tempat output: `riset-directory/04-data/`.
- Infrastruktur: layanan dijalankan secara lokal pada perangkat penguji (tanpa Docker). Jalankan `service-express` dan `service-laravel` secara langsung di host sesuai petunjuk di `riset-directory/05-kode/prototype/README.md`.
- Beban: 100 VU, ramp-up 30s, hold 120s (k6 scenario: `benchmark/k6/complex-crud.js`).
- Replikasi: 10 run independen per kondisi (total 10 run untuk perbandingan berpasangan), masing-masing run dilakukan setelah seed dan warmup.
- Analisis: Agregat per-run (median, p95, throughput, error rate) dianalisis across-replicates menggunakan uji Mann-Whitney U (α=0.05). Ambang practical significance: 15%.

Jadwal pelaksanaan (contoh)
- Hari 1: Persiapan lingkungan, install deps, generate dataset, verifikasi endpoint.
- Hari 2: Run 5 eksperimen (pagi 3, sore 2), verifikasi logs.
- Hari 3: Run 5 eksperimen sisa, kumpulkan dan aggregasi data.
- Hari 4: Analisis statistik dan penulisan hasil awal.

Lokasi keluaran dan log
- Semua log run disimpan di: `riset-directory/06-output/experiment-logs/` (run-01.txt .. run-10.txt).
- Ringkasan hasil dan analisis: `riset-directory/06-output/experiment-summary.md`.

Instruksi singkat eksekusi (PowerShell) — lokal (tanpa Docker)

Prasyarat: Node.js (untuk `service-express` dan skrip seed/benchmark), PHP + Composer (untuk `service-laravel` jika perlu), dan `k6` pada PATH.

```powershell
# dari root workspace
cd riset-directory/05-kode/prototype

# 1) Pasang dependensi (jika belum):
npm install
cd service-express
npm install
cd ..\service-laravel
composer install   # jika menggunakan composer untuk Laravel
cd ..\..

# 2) Generate dataset
cd riset-directory/05-kode/prototype
npm run seed

# 3) Jalankan kedua service secara terpisah di terminal terpisah:
# - Express: cd service-express; npm start
# - Laravel: cd service-laravel; php artisan serve --port=8001   (atau cara start yang sesuai)

# 4) Setelah kedua service dilaporkan aktif, jalankan runner:
powershell .\scripts\run-experiments.ps1
```

Referensi dan materi teori
- Dokumen teori dan literatur lama tersedia di arsip: [riset-directory/archived/03-teori/](riset-directory/archived/03-teori/)
- Proposal utama: [riset-directory/01-proposal/proposal.md](riset-directory/01-proposal/proposal.md)

Catatan: eksekusi otomatis tergantung pada ketersediaan Docker, k6, dan Node.js pada mesin yang digunakan. Jika lingkungan tidak memiliki Docker, jalankan `npm run benchmark` secara manual sesuai README prototype.
