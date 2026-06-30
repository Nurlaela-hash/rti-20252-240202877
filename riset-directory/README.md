# Perbandingan Performa Laravel dan Express.js pada REST API CRUD Kompleks

**Judul:** Performance Evaluation of Laravel and Express.js backend frameworks for complex CRUD operations under high concurrency

**Target publikasi:** Sinta 2 (Jurnal JUTIF / RESTI) atau Seminar Nasional

## Ringkasan

Penelitian ini mengevaluasi dan membandingkan performa antara dua framework backend terpopuler, yaitu **Laravel 11 (PHP 8.4)** dan **Express.js 4.x (Node.js 20)**. Skenario yang diuji berupa REST API CRUD kompleks yang melayani kueri multi-join lintas 5 tabel relasional (categories, brands, products, inventory, reviews) serta proses agregasi, filter dinamis, dan kalkulasi pagination pada dataset e-commerce berisi **100.000 baris data**. Pengujian performa dilakukan menggunakan **k6** dengan menyimulasikan 20 Virtual Users (VU) serentak selama total 70 detik per run. Kedua framework diuji dalam wadah terisolasi menggunakan **Docker Compose** dengan pembatasan sumber daya perangkat keras (1.0 CPU Core & 512MB RAM untuk API, 2.0 CPU Cores & 1024MB RAM untuk MySQL) demi keadilan pengujian.

Detail lengkap topik & roadmap: [09-docs/rencana-penelitian.md](09-docs/rencana-penelitian.md)

## Struktur Direktori

| Folder | Isi |
|---|---|
| [00-admin/](00-admin/) | Administrasi penelitian (jadwal, log pelaksanaan) |
| [01-proposal/](01-proposal/) | Proposal penelitian komprehensif |
| [02-literatur/](02-literatur/) | Tinjauan pustaka & referensi paper terkait |
| [03-teori/](03-teori/) | Landasan teori & arsitektur eksperimen |
| [04-data/](04-data/) | Data mentah MySQL dump (seed.sql) |
| [05-kode/](05-kode/) | Source code: express, laravel, dan skrip k6 (prototype) |
| [06-output/](06-output/) | Statistik & logs pengujian (experiment-logs) |
| [07-manuskrip/](07-manuskrip/) | Draf naskah jurnal |
| [08-laporan/](08-laporan/) | Laporan akhir penelitian |
| [09-docs/](09-docs/) | Dokumen perencanaan & peta jalan tiap tahap penelitian |

## Status Tahapan

- [x] **Tahap 1** — Perancangan Arsitektur & Skema Database — *Selesai* ([detail](09-docs/tahap-1-arsitektur-dan-skema-database.md))
- [x] **Tahap 2** — Implementasi Service Express & Laravel — *Selesai* ([detail](09-docs/tahap-2-implementasi-service-express-dan-laravel.md))
- [x] **Tahap 3** — Skrip & Eksekusi Pengujian k6 — *Selesai* ([detail](09-docs/tahap-3-pengujian-k6.md))
- [x] **Tahap 4** — Ekstraksi Data & Analisis Hasil — *Selesai* ([detail](09-docs/tahap-4-analisis-data.md))
- [x] **Tahap 5** — Draf Laporan Akhir & Hasil Evaluasi — *Selesai* ([detail](09-docs/tahap-5-draf-paper.md))

## Laporan Penelitian

Laporan penelitian komprehensif (ringkasan eksekutif, metodologi, hasil statistik, kesimpulan): [08-laporan/laporan-penelitian.md](08-laporan/laporan-penelitian.md)

## Author

Nurlaela (NIM: 240202877)
