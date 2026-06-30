# Laporan Penelitian Akhir

**Judul:** Perbandingan Performa Laravel dan Express.js pada REST API CRUD Kompleks (100K+ rows)

**Peneliti:** Nurlaela (NIM: 240202877)
**Target Publikasi:** Jurnal JUTIF / Jurnal RESTI (Sinta 2)
**Status Penelitian:** Tahap 1–5 selesai; Dokumentasi dan laporan akhir siap diserahkan.

---

## 1. Ringkasan Eksekutif

Penelitian ini melakukan komparasi performa empiris terkontrol antara **Laravel 11 (PHP 8.4)** dan **Express.js 4.x (Node.js 20)** dalam menangani REST API CRUD kompleks pada dataset e-commerce berisi **100.000 baris data**. Eksperimen dijalankan secara terisolasi menggunakan **Docker Compose** dengan membatasi kapasitas CPU dan RAM kontainer aplikasi secara setara (1.0 CPU Core & 512MB RAM). Pengujian beban konkurensi diinjeksi oleh **k6** menyimulasikan 20 Virtual Users (VU) selama total 70 detik per run dengan 10 replikasi independen per framework.

**Temuan Utama:**
* **Efisiensi Latensi Rata-rata**: Laravel 11 menghasilkan latensi rata-rata yang **22.00% lebih cepat** dibandingkan Express.js (3350.00 ms vs 4295.00 ms).
* **Throughput (RPS)**: Laravel 11 menghasilkan throughput rata-rata yang **24.22% lebih tinggi** dibandingkan Express.js (2.72 req/s vs 2.19 req/s).
* **Stabilitas Tail Latency (p95)**: Laravel 11 memiliki latensi persentil ke-95 yang **40.64% lebih rendah** dibandingkan Express.js (5832.00 ms vs 9825.00 ms).
* **Keandalan sistem**: Express.js mencatat error rate 0.00%, sedangkan Laravel mencatat error rate sangat kecil (0.05%) akibat penumpukan antrean pada beban CPU penuh, yang masih jauh di bawah batas kegagalan toleransi eksperimen (<5.00%).

Hasil pengolahan data mentah k6 secara detail dapat diakses di file log [riset-directory/06-output/experiment-logs/](file:///F:/Nurlaela/rti-20252-240202877/riset-directory/06-output/experiment-logs/) dan dataset MySQL cadangan di [riset-directory/04-data/seed.sql](file:///F:/Nurlaela/rti-20252-240202877/riset-directory/04-data/seed.sql).

---

## 2. Latar Belakang dan Rumusan Masalah

### 2.1 Latar Belakang
Keputusan arsitektur dalam menentukan framework backend di industri sering kali didasarkan pada bias subjektif tim pengembang atau tren, alih-alih data empiris berskala produksi. Sebagian besar ulasan benchmark yang beredar hanya menggunakan database berskala sangat kecil (di bawah 1.000 data) dan kueri CRUD sederhana, yang tidak mencerminkan kompleksitas aplikasi relasional modern (multi-join, agregasi, filter, dan pagination). Hal ini berisiko menyebabkan kegagalan penskalaan sistem saat jam puncak trafik dan membengkaknya pengeluaran infrastruktur cloud.

### 2.2 Rumusan Masalah
1. Sejauh mana perbedaan performa latensi rata-rata dan p95 antara Laravel 11 dan Express.js 4.x dalam menangani CRUD kompleks e-commerce pada dataset relasional 100K baris di bawah konkurensi tinggi?
2. Bagaimana perbandingan efisiensi throughput (RPS) dari kedua framework di bawah limitasi sumber daya CPU dan RAM kontainer yang setara?
3. Mengapa disparitas performa terjadi ketika kedua runtime diletakkan pada batas CPU jenuh (*CPU saturation*)?

---

## 3. Metodologi dan Pelaksanaan

Riset dilaksanakan dalam 5 tahap sekuensial:

### 3.1 Tahap 1 — Perancangan Arsitektur & Skema Database
* **Status: Selesai.** [Detail Tahap 1](../09-docs/tahap-1-arsitektur-dan-skema-database.md)
* **Aktivitas**: Merancang database relasional 5 tabel (categories, brands, products, inventory, reviews) serta mengunci batasan resource kontainer Docker (1 CPU, 512MB RAM) demi menjamin pengujian terkontrol.

### 3.2 Tahap 2 — Implementasi Service Express & Laravel
* **Status: Selesai.** [Detail Tahap 2](../09-docs/tahap-2-implementasi-service-express-dan-laravel.md)
* **Aktivitas**: Mengimplementasikan endpoint ekuivalen pada Express (Node.js 20) dan Laravel 11 (PHP 8.4) menggunakan raw SQL parameter. Mematikan database session & cache pada Laravel untuk memastikan perbandingan yang adil.

### 3.3 Tahap 3 — Skrip & Eksekusi Pengujian k6
* **Status: Selesai.** [Detail Tahap 3](../09-docs/tahap-3-pengujian-k6.md)
* **Aktivitas**: Membuat runner PowerShell `run-experiments.ps1` untuk mengotomatiskan ekspor database dump ke `riset-directory/04-data/seed.sql` dan mengeksekusi load test k6 sebanyak 10 putaran per framework secara terisolasi.

### 3.4 Tahap 4 — Ekstraksi Data & Analisis Hasil
* **Status: Selesai.** [Detail Tahap 4](../09-docs/tahap-4-analisis-data.md)
* **Aktivitas**: Menyusun program python parser `parse_results.py` untuk mengolah file log k6 biner UTF-16, mengekstrak nilai mean dan std deviasi untuk RPS serta latensi (avg, med, p95).

### 3.5 Tahap 5 — Laporan Akhir & Evaluasi
* **Status: Selesai.** [Detail Tahap 5](../09-docs/tahap-5-draf-paper.md)
* **Aktivitas**: Menyusun dokumen draf final laporan penelitian dan merumuskan saran pengembangan riset.

---

## 4. Analisis & Pembahasan Performa

| Metrik Evaluasi | Express.js (Mean ± Std) | Laravel 11 (Mean ± Std) | Selisih Performa |
|---|---|---|---|
| **Throughput (RPS)** | 2.19 ± 0.09 req/s | **2.72 ± 0.12 req/s** | +24.22% (Laravel Unggul) |
| **Avg Latency** | 4295.00 ± 181.78 ms | **3350.00 ± 155.31 ms** | -22.00% (Laravel Unggul) |
| **Median Latency** | **3482.00 ± 271.62 ms** | 4069.00 ± 214.54 ms | -16.85% (Express Unggul) |
| **p95 Latency** | 9825.00 ± 373.56 ms | **5832.00 ± 365.45 ms** | -40.64% (Laravel Unggul) |
| **Error Rate** | **0.00 ± 0.00%** | 0.05 ± 0.15% | - |

**Analisis Kausalitas Beban:**
1. **Model Concurrency**: PHP 8.4 dengan 4 CLI workers menggunakan forking proses OS untuk menangani konkurensi. Saat kueri database memblokir I/O, OS scheduler dapat menukar proses yang terblokir dengan worker lain. Sementara itu, Express.js (single event loop) menampung seluruh callback asinkron dalam satu utas. Di bawah pembebanan CPU 100%, overhead manajemen callback asinkron Node.js dan konkurensi tinggi menyebabkan latensi antrean internal melonjak lebih tajam.
2. **Kinerja p95 Latency**: Persentil ke-95 Express.js sangat buruk (9825.00 ms) dibandingkan Laravel (5832.00 ms). Ini adalah efek *head-of-line blocking* di mana request yang datang belakangan terhambat oleh proses I/O kompleks di depan utas tunggal Node.js yang sudah mengalami saturasi CPU.

---

## 5. Kesimpulan & Kerja Masa Depan

### 5.1 Kesimpulan
Penelitian empiris ini membuktikan bahwa di bawah pembatasan resource perangkat keras yang ketat (1.0 CPU, 512MB RAM) dengan beban database kompleks 100K baris, framework **Laravel 11 (PHP 8.4) secara signifikan mengungguli Express.js (Node.js 20)** dalam throughput (+24%) dan efisiensi rata-rata latensi (+22%), dengan tail latency (p95) yang jauh lebih stabil (-40%).

### 5.2 Kerja Masa Depan
1. Membandingkan performa menggunakan runtime server produksi (seperti Nginx + PHP-FPM / Swoole vs PM2 Node.js cluster).
2. Mengevaluasi overhead ORM (Eloquent vs Sequelize) dalam mengelola kueri relasional.
3. Melakukan pengujian horizontal scaling dengan klaster server database terpisah.
