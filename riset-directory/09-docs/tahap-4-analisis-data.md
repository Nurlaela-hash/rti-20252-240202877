# Tahap 4 — Ekstraksi Data & Analisis Hasil

**Status:** Selesai (Statistik deskriptif berhasil diekstraksi dari 20 berkas log)
**Bergantung pada:** [tahap-3-pengujian-k6.md](tahap-3-pengujian-k6.md)
**Lokasi utilitas parser:** [../06-output/parse_results.py](../06-output/parse_results.py)

---

## 1. Tujuan

Mengekstrak data performa mentah dari berkas log pengujian k6 UTF-16, menghitung rata-rata (*mean*) dan standar deviasi (*std*) untuk metrik latensi, throughput, dan tingkat kegagalan (*error rate*) lintas 10 kali putaran (*runs*).

## 2. Hasil Pengolahan Statistik (n=10)

Berikut adalah rangkuman statistik performa yang dihitung oleh utilitas `parse_results.py`:

### Express.js (Node.js 20-alpine, 1 CPU / 512MB RAM)
* **Average Response Time**: 4295.00 ms (Std: 181.78 ms)
* **Median Response Time**: 3482.00 ms (Std: 271.62 ms)
* **p95 Response Time**: 9825.00 ms (Std: 373.56 ms)
* **Throughput (RPS)**: 2.19 req/s (Std: 0.09)
* **Total Requests**: 157.40 requests per run (Std: 6.50)
* **Error Rate**: 0.00% (Std: 0.00)

### Laravel 11 (PHP 8.4-cli-alpine, 1 CPU / 512MB RAM)
* **Average Response Time**: 3350.00 ms (Std: 155.31 ms)
* **Median Response Time**: 4069.00 ms (Std: 214.54 ms)
* **p95 Response Time**: 5832.00 ms (Std: 365.45 ms)
* **Throughput (RPS)**: 2.72 req/s (Std: 0.12)
* **Total Requests**: 196.30 requests per run (Std: 8.09)
* **Error Rate**: 0.05% (Std: 0.15)

---

## 3. Analisis Hasil & Kausalitas

1. **Perbandingan Latensi & Throughput**:
   * Laravel 11 (`PHP_CLI_SERVER_WORKERS=4`) secara mengejutkan menghasilkan **throughput yang lebih tinggi** (2.72 RPS vs 2.19 RPS) dan **latensi rata-rata yang lebih rendah** (3350 ms vs 4295 ms) dibandingkan Express.js dalam pengujian ini.
   * Ini menunjukkan bahwa pada tingkat konkurensi sedang (20 VU) dengan kueri basis data relasional yang sangat berat, pemrosesan multi-process Laravel (4 workers) yang didistribusikan oleh penjadwal OS lebih tangguh menghadapi hambatan I/O database MySQL dibandingkan dengan event loop Express.js yang bersifat single-threaded saat di-limit pada **1.0 CPU Core**.
2. **Kinerja Tail Latency (p95)**:
   * Express.js menunjukkan lonjakan latensi persentil ke-95 yang sangat tinggi (9825 ms vs 5832 ms). Hal ini mengindikasikan antrean kueri basis data pada I/O non-blocking Node.js menumpuk di memori ketika CPU mencapai kapasitas maksimum (*CPU saturation* pada 1.0 core), menyebabkan request di bagian belakang antrean mengalami penundaan yang signifikan (*head-of-line blocking* pada tingkat aplikasi).
3. **Keandalan (Error Rate)**:
   * Express.js memiliki tingkat kegagalan murni 0.00%. Laravel memiliki error rate sangat kecil yaitu 0.05% (disebabkan oleh satu request timeout/kegagalan koneksi sesaat di run 10 akibat beban CPU penuh), yang masih jauh di bawah ambang batas validitas eksperimen (toleransi error < 5.00%).
