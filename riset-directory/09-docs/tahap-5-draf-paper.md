# Tahap 5 — Draf Paper & Evaluasi Hasil Akhir

**Status:** Selesai (Draf laporan akhir terintegrasi)
**Bergantung pada:** [tahap-4-analisis-data.md](tahap-4-analisis-data.md)
**Lokasi draf akhir:** [../08-laporan/laporan-penelitian.md](../08-laporan/laporan-penelitian.md)

---

## 1. Kesimpulan Akhir Riset

Berdasarkan data empiris dari 10 run independen pada 20 VU dengan dataset e-commerce 100.000 baris di bawah limitasi perangkat keras kontainer Docker (1 CPU, 512MB RAM):

1. **Efisiensi Throughput**: Laravel 11 mengungguli Express.js sebesar **24.22%** dalam throughput (RPS rata-rata 2.72 vs 2.19).
2. **Latensi Rata-rata**: Laravel 11 lebih cepat **22.00%** dalam melayani HTTP request dibandingkan Express.js (3350 ms vs 4295 ms).
3. **Tail Latency (p95)**: Laravel 11 memiliki latensi persentil ke-95 yang **40.64% lebih rendah** (5832 ms vs 9825 ms), menunjukkan stabilitas performa antrean yang lebih baik di bawah limitasi resource yang ketat.

## 2. Implikasi Pengambilan Keputusan Arsitektural

* **Konteks Resource-Constrained**: Pada skenario cloud hosting murah (misalnya VM 1 vCPU / 512MB RAM) yang menjalankan kueri database yang kompleks, runtime PHP 8.4 dengan konfigurasi multi-worker (seperti 4 CLI workers) terbukti lebih efisien dalam membagi beban komputasi/I/O dibandingkan dengan single-threaded event loop Node.js (Express.js).
* **Beban I/O Database vs Komputasi**: Node.js sering dianggap lebih cepat untuk I/O asinkron ringan. Namun, ketika kueri database sangat kompleks (seperti join 5 tabel relasional dengan agregasi rating), kemacetan utama (*bottleneck*) bergeser ke MySQL dan pemrosesan JSON di tingkat aplikasi. Di bawah saturasi CPU 100%, event loop Node.js mengalami pemblokiran internal yang menaikkan latensi antrean secara eksponensial.

## 3. Batasan Penelitian & Arah Kerja Masa Depan

* **Model Concurrency**: Penelitian ini menggunakan built-in PHP CLI development server dengan worker forking. Evaluasi menggunakan web server tingkat produksi seperti Nginx + PHP-FPM, RoadRunner, atau Swoole/FrankenPHP vs PM2 cluster pada Node.js layak diuji untuk mendapatkan gambaran performa pada sistem skala besar.
* **ORM vs Raw SQL**: Kedua service diuji menggunakan kueri SQL mentah (*raw SQL*). Pengaruh overhead ORM (Eloquent vs Sequelize/Prisma) dapat menjadi variabel bebas tambahan pada pengujian berikutnya.
* **Volume Data**: Pengujian dapat ditingkatkan ke skala jutaan baris data relasional untuk menganalisis titik patah (*break-even point*) skalabilitas framework.
