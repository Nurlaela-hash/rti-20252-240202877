# Prototype Perbandingan Laravel vs Express.js

Prototype ini digunakan untuk membandingkan performa REST API CRUD kompleks antara Express.js dan Laravel pada dataset 100K baris. Prototype ini dijalankan secara lokal tanpa Docker.

## Isi Prototype

- `db/schema.sql` - skema database 5 tabel yang dipakai bersama.
- `scripts/seed-database.js` - seed deterministik untuk 100K rows.
- `benchmark/k6/complex-crud.js` - skenario load test sesuai proposal.
- `service-express/` - implementasi REST API Express.js.
- `service-laravel/` - aplikasi Laravel yang menyajikan endpoint benchmark yang sama.
- `openapi.yaml` - kontrak API bersama agar fairness terjaga.

## Kesesuaian dengan Proposal

- Variabel independen: framework backend.
- Variabel dependen: response time, throughput, error rate, CPU, memory.
- Kontrol: database, skema, query kompleks, dan load profile identik.
- Instrumen: `k6`, log aplikasi, dan MySQL.
- Eksperimen: warmup terpisah, lalu run utama dengan beban 100 VU.

## Prasyarat

- Node.js + npm
- PHP 8.2+ dan Composer
- MySQL lokal yang aktif
- k6

## Langkah Menjalankan Secara Lokal

1. Salin file `.env.example` di root prototype menjadi `.env`, lalu sesuaikan nilai database.
2. Salin file `service-laravel/.env.example` menjadi `service-laravel/.env`, lalu isi koneksi database yang sama.
3. Buat database MySQL yang dipakai, misalnya:

   ```sql
   CREATE DATABASE benchmark_rti CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

4. Instal dependensi root:

   ```powershell
   npm install
   ```

5. Jalankan service di terminal terpisah.

   Express:

   ```powershell
   cd service-express
   npm install
   npm start
   ```

   Laravel:

   ```powershell
   cd service-laravel
   composer install
   php artisan serve --port=8001
   ```

6. Seed data benchmark dari root prototype:

   ```powershell
   npm run seed
   ```

7. Jalankan benchmark.

   Untuk Express:

   ```powershell
   $env:K6_BASE_URL = "http://localhost:3000"
   $env:TARGET_FRAMEWORK = "express"
   k6 run benchmark/k6/complex-crud.js
   ```

   Untuk Laravel:

   ```powershell
   $env:K6_BASE_URL = "http://localhost:8001"
   $env:TARGET_FRAMEWORK = "laravel"
   k6 run benchmark/k6/complex-crud.js
   ```

## Catatan Penting

- Dataset dibuat deterministik dengan seed agar hasil bisa diulang.
- Query list produk memakai join ke kategori, brand, inventory, dan agregasi review.
- Prototype ini dirancang untuk eksekusi lokal dan tidak lagi menggunakan Docker.
