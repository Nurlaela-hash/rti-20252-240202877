# Tahap 3 — Skrip & Eksekusi Pengujian k6

**Status:** Selesai (10 run per framework berhasil dieksekusi)
**Bergantung pada:** [tahap-2-implementasi-service-express-dan-laravel.md](tahap-2-implementasi-service-express-dan-laravel.md)
**Lokasi kode:** [../05-kode/prototype/benchmark/k6/](../05-kode/prototype/benchmark/k6)

---

## 1. Tujuan

Menyusun skenario pengujian konkurensi k6 untuk mensimulasikan beban kerja realistis e-commerce dan mengeksekusinya 10x per framework secara terisolasi.

## 2. Struktur Pengujian

* **Skrip Pengujian**: `benchmark/k6/complex-crud.js`
* **Profil Beban**: 
  * Fase *Warmup* (Pemanasan): 30 detik (tidak dihitung dalam metrik hasil)
  * Fase *Ramp-up*: 10 detik
  * Fase *Sustain* (Hold): 20 detik
  * *Concurrency*: 20 Virtual Users (VU)
* **Distribusi Request**:
  * **68%** Listing Produk (Kueri multi-join kompleks + filter harga & status + pagination)
  * **18%** View Detail Produk (Show single product + LEFT JOIN review rating)
  * **6%** Create Product (Transaction INSERT ke tabel products & inventory)
  * **5%** Update Product (Transaction UPDATE ke tabel products & inventory)
  * **3%** Delete Product (Transaction DELETE ke tabel products, reviews, & inventory)

## 3. Eksekutor Otomatis (`run-experiments.ps1`)

Untuk meminimalkan variasi kesalahan manusia, script PowerShell `scripts/run-experiments.ps1` dibuat untuk mengotomatiskan seluruh alur:
1. Mengekspor seed database MySQL yang aktif ke `riset-directory/04-data/seed.sql` via `docker compose exec -T mysql mysqldump`.
2. Menjalankan load-testing k6 di dalam kontainer Docker (`grafana/k6:0.54.0`).
3. Mengalirkan log keluaran k6 secara mentah langsung ke file teks host: `run-[framework]-[run-number].txt` di dalam folder `riset-directory/06-output/experiment-logs/`.
4. Melakukan jeda (*Sleep*) selama 5 detik antar run untuk pemulihan siklus koneksi database.

## 4. Hasil Eksekusi

Sebanyak 20 run (10 Express, 10 Laravel) berhasil diselesaikan tanpa gangguan dengan status exit code `0`. Seluruh log pengujian tersimpan di:
* [riset-directory/06-output/experiment-logs/](file:///F:/Nurlaela/rti-20252-240202877/riset-directory/06-output/experiment-logs/)
  * `run-express-01.txt` s.d. `run-express-10.txt`
  * `run-laravel-01.txt` s.d. `run-laravel-10.txt`
* Berkas data seed SQL hasil eksekusi berhasil dicadangkan di:
  * [riset-directory/04-data/seed.sql](file:///F:/Nurlaela/rti-20252-240202877/riset-directory/04-data/seed.sql) (~61.6 MB)
