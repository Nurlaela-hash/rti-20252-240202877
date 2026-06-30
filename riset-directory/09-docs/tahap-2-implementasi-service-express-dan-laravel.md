# Tahap 2 — Implementasi Service Express.js & Laravel

**Status:** Selesai
**Acuan arsitektur:** [tahap-1-arsitektur-dan-skema-database.md](tahap-1-arsitektur-dan-skema-database.md)
**Lokasi kode:** [../05-kode/prototype/](../05-kode/prototype/)

---

## 1. Tujuan

Mengimplementasikan service backend REST API CRUD ekuivalen pada Express.js dan Laravel 11 sesuai kontrak `openapi.yaml`.

## 2. Rincian Implementasi

### Service Express.js (`service-express/`)
* **Runtime**: Node.js 20-alpine (`platform: linux/amd64`).
* **Koneksi Database**: Menggunakan package driver `mysql2/promise` dengan modul ES6. Connection pooling dikonfigurasi melalui variabel lingkungan:
  * `DB_CONNECTION_LIMIT=10`
* **Logika Query**: Menggunakan raw SQL kueri parametrik (bukan ORM berat Sequelize) untuk menjamin latensi pemrosesan I/O Node.js seoptimal mungkin.
* **Dockerfile**: Menggunakan package-lock.json dan instalasi deterministik via `npm ci --omit=dev`.

### Service Laravel 11 (`service-laravel/`)
* **Runtime**: PHP 8.4-cli-alpine (`platform: linux/amd64`).
* **Optimasi Concurrency**: Built-in PHP server berjalan dengan memanfaatkan forking process worker via:
  * `PHP_CLI_SERVER_WORKERS=4`
* **Koneksi Database**: Menggunakan driver PDO bawaan Laravel (`DB::select` dan raw transactions).
* **Dockerfile**: Mengintegrasikan `composer:2` dan melakukan kompilasi dependensi runtime via `composer install --no-dev`. Direktori `storage` dan `bootstrap/cache` diberi perizinan chmod `777`.

## 3. Hasil Verifikasi Fungsional

Kedua service diverifikasi berjalan normal menggunakan endpoint `/health` dan `/api/products` (GET, POST, PUT, DELETE). Respons JSON yang dihasilkan identik secara struktur:

* **Express.js `/health`**:
  ```json
  {"status":"ok","service":"express","timestamp":"2026-06-30T18:35:45.503Z"}
  ```
* **Laravel `/health`**:
  ```json
  {"status":"ok","service":"laravel","timestamp":"2026-06-30T18:35:45.681478Z"}
  ```

## 4. Penyelarasan Konfigurasi Kontrol

Demi menjamin hasil perbandingan yang valid (hanya membandingkan overhead internal runtime/bahasa framework):
* Skenario kueri list produk yang diuji pada Express dan Laravel menggunakan SQL yang **sama persis**, termasuk LEFT JOIN agregasi reviews (kalkulasi rating dan rating count) lintas 5 tabel relasional dengan limitasi pagination.
* Semua layer middleware session dan caching dinonaktifkan di kedua framework.
