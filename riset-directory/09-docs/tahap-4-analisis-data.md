# Tahap 4 — Ekstraksi Data & Analisis Hasil

**Status:** Selesai (Statistik deskriptif berhasil diekstraksi dari 20 berkas log)
**Bergantung pada:** [tahap-3-pengujian-k6.md](tahap-3-pengujian-k6.md)
**Lokasi utilitas parser:** [../06-output/parse_results.py](../06-output/parse_results.py)

---

## 1. Tujuan

Mengekstrak data performa mentah dari berkas log pengujian k6 UTF-16, menghitung rata-rata (*mean*) dan standar deviasi (*std*) untuk metrik latensi, throughput, dan tingkat kegagalan (*error rate*) lintas 10 kali putaran (*runs*).

## 2. Pra-pemrosesan Data (Data Preprocessing)

Sebelum melakukan analisis statistik, data mentah dari log pengujian k6 melewati pipa pembersihan (*data refinement pipeline*) untuk menjamin integritas dan reproduksibilitas:

### 2.1 Pembersihan Data (Data Cleaning)
* **Penanganan Masalah Encoding**: Berkas log yang dialirkan oleh PowerShell menggunakan format UTF-16 dengan BOM (*Byte Order Mark*). Utilitas parser [parse_results.py](../06-output/parse_results.py) didekomposisi untuk melakukan dekode sekuensial mencoba `utf-16-le`, `utf-16-be`, dan `utf-8` dengan parameter `errors='ignore'` untuk membersihkan karakter noise.
* **Pembersihan Kebisingan (Noise Cleaning)**: Karakter grafik CLI k6 (seperti `╡`, `╳`, atau tanda kurung) dibersihkan untuk menghindari kegagalan pencocokan ekspresi reguler.
* **Kelengkapan Data**: Terverifikasi 20 berkas log lengkap (10 runs Express, 10 runs Laravel) tanpa ada data yang hilang (*missing values*) atau duplikat.

### 2.2 Transformasi & Standardisasi Unit
* **Ekstraksi Reguler (Regex)**: Baris log teks dibaca untuk mengekstrak metrik kinerja (`duration_avg`, `duration_med`, `duration_p95`, `reqs_rps`, `reqs_total`, dan `failed_pct`).
* **Standardisasi Unit Waktu**: Durasi respons k6 bersifat dinamis (menggunakan detik `s`, milidetik `ms`, atau mikrodetik `µs`). Parser melakukan standardisasi otomatis: jika satuannya adalah detik (`s`), nilainya dikalikan 1000.0; jika mikrodetik (`µs`) atau mengandung karakter gangguan `╡`, nilainya dibagi 1000.0 agar seluruh data latensi seragam dalam milidetik (`ms`).

### 2.3 Keputusan Normalisasi (Normalization Decision)
Riset ini memutuskan untuk **tidak menerapkan normalisasi** (seperti *Min-Max* atau *Z-score*) pada metrik kinerja. Justifikasi keputusan ini meliputi:
1. **Interpretabilitas Praktis**: Metrik performa sistem (Response Time dalam ms dan Throughput dalam RPS) memiliki arti fisik langsung yang sangat krusial bagi praktisi backend. Mengubahnya menjadi nilai skala [0,1] atau z-score akan menghilangkan makna praktisnya.
2. **Ketiadaan Model Berbasis Jarak**: Tidak ada model pembelajaran mesin berbasis jarak (*distance-based ML*) yang digunakan dalam penelitian ini. Pengujian hipotesis statistik dan deskriptif tidak terdistorsi oleh perbedaan skala fisik antar-metrik.

### 2.4 Pencegahan Kebocoran Data (Data Leakage Prevention)
* Parameter statistik deskriptif (*mean* dan *std dev*) dihitung secara independen per skenario framework. Tidak ada pencampuran metrik atau parameter scaling lintas Laravel dan Express.js untuk menghindari bias kebocoran grup.

---

## 3. Hasil Pengolahan Statistik (n=10)

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

### 3.1 Pengujian Hipotesis Statistik

Untuk membuktikan secara ilmiah keabsahan perbedaan performa rata-rata antara Express.js dan Laravel 11, dijalankan analisis uji signifikansi (uji normalitas Shapiro-Wilk diikuti uji Welch's t-test / Mann-Whitney U, n=10 per framework):

1. **Uji Normalitas (Shapiro-Wilk Test)**:
   * **Throughput (`reqs_rps`)**: Express.js melanggar asumsi normalitas ($p = 0.0042 < 0.05$), sedangkan Laravel 11 terdistribusi normal ($p = 0.6791$).
   * **Average Latency (`duration_avg`)**: Express.js melanggar asumsi normalitas ($p = 0.0046 < 0.05$), sedangkan Laravel 11 terdistribusi normal ($p = 0.5471$).
   * **p95 Latency (`duration_p95`)**: Express.js terdistribusi normal ($p = 0.7843$), dan Laravel 11 terdistribusi normal ($p = 0.5759$).
   * *Keputusan Pemilihan Uji*: Karena data Express.js melanggar asumsi normalitas pada throughput dan rata-rata latensi, digunakan uji non-parametrik **Mann-Whitney U Test** untuk membandingkan kedua metrik tersebut. Sedangkan untuk latensi tail (p95) yang memenuhi asumsi normalitas, digunakan uji parametrik **Welch's t-test** (robust terhadap perbedaan varians).

2. **Hasil Signifikansi Uji Hipotesis ($H_0$ vs $H_1$)**:
   * **Throughput (`reqs_rps`)**:
     * Uji Mann-Whitney U: $U = 0.00$, $p = 0.00018$ ($p < 0.001$, sangat signifikan).
     * Effect Size (Cohen's d): $-4.87$ (Efek sangat besar / *large effect*).
     * 95% Confidence Interval untuk selisih (Laravel - Express): $[0.43, 0.64]$ req/s.
     * *Keputusan*: $H_0$ ditolak, keunggulan throughput Laravel 11 terbukti signifikan secara statistik.
   * **Average Latency (`duration_avg`)**:
     * Uji Mann-Whitney U: $U = 100.00$, $p = 0.00018$ ($p < 0.001$, sangat signifikan).
     * Effect Size (Cohen's d): $5.30$ (Efek sangat besar / *large effect*).
     * 95% Confidence Interval untuk selisih (Express - Laravel): $[777.27, 1112.73]$ ms.
     * *Keputusan*: $H_0$ ditolak, keunggulan latensi rata-rata Laravel 11 terbukti signifikan secara statistik.
   * **p95 Latency (`duration_p95`)**:
     * Uji Welch's t-test: $t = 22.92$, $p = 9.13 \times 10^{-15}$ ($p < 0.001$, sangat signifikan secara ekstrem).
     * Effect Size (Cohen's d): $10.25$ (Efek luar biasa besar / *extreme effect*).
     * 95% Confidence Interval untuk selisih (Express - Laravel): $[3627.01, 4358.99]$ ms.
     * *Keputusan*: $H_0$ ditolak, stabilitas tail latency p95 Laravel 11 terbukti signifikan secara statistik.

---

## 4. Analisis Hasil & Kausalitas

1. **Perbandingan Latensi & Throughput**:
   * Laravel 11 (`PHP_CLI_SERVER_WORKERS=4`) secara mengejutkan menghasilkan **throughput yang lebih tinggi** (2.72 RPS vs 2.19 RPS) dan **latensi rata-rata yang lebih rendah** (3350 ms vs 4295 ms) dibandingkan Express.js dalam pengujian ini.
   * Ini menunjukkan bahwa pada tingkat konkurensi sedang (20 VU) dengan kueri basis data relasional yang sangat berat, pemrosesan multi-process Laravel (4 workers) yang didistribusikan oleh penjadwal OS lebih tangguh menghadapi hambatan I/O database MySQL dibandingkan dengan event loop Express.js yang bersifat single-threaded saat di-limit pada **1.0 CPU Core**.
2. **Kinerja Tail Latency (p95)**:
   * Express.js menunjukkan lonjakan latensi persentil ke-95 yang sangat tinggi (9825 ms vs 5832 ms). Hal ini mengindikasikan antrean kueri basis data pada I/O non-blocking Node.js menumpuk di memori ketika CPU mencapai kapasitas maksimum (*CPU saturation* pada 1.0 core), menyebabkan request di bagian belakang antrean mengalami penundaan yang signifikan (*head-of-line blocking* pada tingkat aplikasi).
3. **Keandalan (Error Rate)**:
   * Express.js memiliki tingkat kegagalan murni 0.00%. Laravel memiliki error rate sangat kecil yaitu 0.05% (disebabkan oleh satu request timeout/kegagalan koneksi sesaat di run 10 akibat beban CPU penuh), yang masih jauh di bawah ambang batas validitas eksperimen (toleransi error < 5.00%).
