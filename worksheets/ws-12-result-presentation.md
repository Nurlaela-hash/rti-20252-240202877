# WS-12: Result Presentation & Visualization

> **Bab 12 — Penyajian Hasil & Visualisasi**

---

## Ringkasan Materi

### Data → Insight Model

```
Validated Data → Structured Presentation → Visualization → Pattern Recognition → Insight
```

Penyajian **mendahului** analisis. Tabel dan grafik membantu peneliti "melihat" data sebelum menghitung. Langsung ke uji statistik tanpa visualisasi berisiko kesimpulan yang secara teknis benar tapi kontekstual salah (Anscombe's Quartet, 1973).

### Tabel = Presisi, Grafik = Pola

Keduanya **saling melengkapi**:
- Tabel: angka presisi, self-contained (dipahami tanpa teks), sortable
- Grafik: pola visual, tren, perbandingan cepat

### Jenis Grafik Berdasarkan Tujuan

| Tujuan | Jenis Grafik |
|--------|-------------|
| Perbandingan antar-skenario | Bar chart (grouped/stacked) |
| Distribusi per-skenario | Box plot / violin plot |
| Tren temporal | Line chart |
| Korelasi dua variabel | Scatter plot |
| Proporsi (total = 100%) | Pie chart (hati-hati!) |

### Contoh Tabel Hasil yang Baik

| Model | Accuracy (%) | F1-Score (%) | Training Time (min) |
|-------|-------------|-------------|---------------------|
| BERT | 88.4 ± 1.2 | 87.1 ± 1.4 | 45.2 ± 3.1 |
| LSTM | 86.1 ± 1.8 | 84.5 ± 2.0 | 12.8 ± 1.2 |
| SVM | 82.3 ± 0.9 | 80.7 ± 1.1 | 0.3 ± 0.1 |

*N=10 per model. Mean ± std. Diurutkan berdasarkan Accuracy.*

### Visualization Bias — Yang Harus Dihindari

| Bias | Deskripsi | Dampak |
|------|----------|--------|
| Truncated axis | Y tidak dari 0 | Memperbesar perbedaan kecil |
| Inconsistent scale | Dua grafik skala beda | Perbandingan menyesatkan |
| Cherry-picked data | Hanya tampilkan yang "menang" | Selektif, tidak jujur |
| 3D effects | Efek 3D tanpa dimensi data ke-3 | Distorsi tanpa informasi |
| Missing error bar | Tidak ada variabilitas | Menyembunyikan ketidakpastian |

### Engineering vs Research Presentation

| Aspek | Engineering | Research |
|-------|-----------|---------|
| Tujuan grafik | Dashboard monitoring | Mendukung argumen ilmiah |
| Informasi wajib | KPI, threshold | Mean, std, CI, N, p-value |
| Bias handling | Less critical | Wajib dihindari (peer-review) |

---

## Template A.12 — Result Presentation Plan

```
RESULT PRESENTATION PLAN

Research Question : Manakah framework yang memiliki performa throughput (RPS) dan latensi lebih baik di bawah saturasi CPU antara Laravel dan Express.js?
Metrik Utama      : Throughput (RPS) dan Average Latency (ms)

Tabel Hasil:
| Skenario | Throughput (mean ± std, req/s) | Avg Latency (mean ± std, ms) | n |
|----------|----------------------|----------------------|---|
| Laravel 11 (PHP 8.4) | 2.72 ± 0.12 | 3350.00 ± 155.31 | 10 |
| Express.js (Node.js 20) | 2.19 ± 0.09 | 4295.00 ± 181.78 | 10 |

Visualisasi yang Direncanakan:
| # | Jenis Grafik | Pesan Utama | Metrik |
|---|-------------|-------------|--------|
| 1 | Bar Chart + error bar | Laravel 11 menghasilkan throughput rata-rata lebih tinggi (+24%) dibanding Express.js | RPS rata-rata ± standar deviasi |
| 2 | Box Plot | Distribusi tail latency (p95) Laravel 11 jauh lebih rendah dan lebih stabil dibanding Express.js | p95 latency dari seluruh run |

Bias Check:
  [x] Y-axis mulai dari 0 (atau dijustifikasi)
  [x] Error bar/CI ditampilkan
  [x] Semua data disertakan (tidak cherry-picked)
  [x] Tidak menggunakan 3D tanpa alasan
```

---

## Latihan 1 — Tabel Hasil

Buat tabel hasil eksperimen Anda (boleh dengan data simulasi jika belum punya data riil).

| Skenario | Throughput (mean ± std, req/s) | Avg Latency (mean ± std, ms) | n |
|----------|----------------------|----------------------|---|
| Laravel 11 (PHP 8.4) | 2.72 ± 0.12 | 3350.00 ± 155.31 | 10 |
| Express.js (Node.js 20) | 2.19 ± 0.09 | 4295.00 ± 181.78 | 10 |

**Checklist tabel:**
- [x] Self-contained (judul jelas, satuan ada, N tercantum)
- [x] Mean ± std (bukan single number)
- [x] Diurutkan berdasarkan metrik utama (Throughput)
- [x] Format konsisten di semua baris


---

## Latihan 2 — Rencana Visualisasi

Rencanakan 2-3 grafik untuk menyajikan data dari Latihan 1. Setiap grafik = satu pesan.

| # | Jenis Grafik | Pesan | Data yang Digunakan |
|---|-------------|-------|---------------------|
| 1 | Bar chart + error bar | Perbandingan throughput (RPS) rata-rata antara Express.js dan Laravel | Mean throughput (req/s) ± std |
| 2 | Box plot | Perbandingan persebaran tail latency (p95) antara kedua framework | p95 latency (ms) dari seluruh 20 run |
| 3 | Scatter plot | Pola korelasi / trade-off antara Throughput vs Average Response Time | Mean throughput vs Mean latency per run |


---

## Latihan 3 — Bias Detection

Evaluasi visualisasi berikut untuk bias (skenario dari contoh):

**Skenario:** Metode A = 91.2%, Metode B = 90.8%. Bar chart dengan Y-axis mulai dari 90%.

| Pertanyaan | Jawaban |
|-----------|---------|
| Apakah Y-axis menyesatkan? | Ya — Metode A terlihat seolah-olah memiliki performa dua kali lipat lebih baik daripada Metode B, padahal perbedaan riilnya hanya 0.4%. |
| Apakah error bar ditampilkan? | Tidak — Ini menyembunyikan variabilitas data sehingga perbedaan kecil tersebut nampak pasti dan signifikan padahal bisa jadi hanyalah noise. |
| Apakah semua kondisi ditampilkan? | Ya — Semua kondisi (Metode A dan Metode B) ditunjukkan. |
| Apa solusinya? | Mengatur Y-axis dimulai dari nilai 0% agar proporsional, serta menambahkan error bar (standar deviasi) untuk visualisasi yang jujur. |

**Evaluasi grafik Anda sendiri dari Latihan 2:**
- [x] Semua bias check lulus
- [x] Ada yang perlu diperbaiki: Tidak ada, sumbu Y pada seluruh chart (RPS & Latensi) dipastikan mulai dari 0 dan dilengkapi error bar standar deviasi.


---

## Refleksi

> Mengapa tabel dan grafik keduanya diperlukan — tidak cukup salah satu saja? Pernahkah Anda membuat grafik yang (tanpa sengaja) menyesatkan?

Tabel dan grafik saling melengkapi karena melayani fungsi kognitif yang berbeda. Tabel memberikan ketepatan angka presisi hingga ke detail desimal untuk kebutuhan analisis mendalam dan audit data. Sementara grafik memberikan pemahaman makro yang cepat mengenai pola, tren, pencilan, dan perbandingan antar-skenario secara visual.

Ya, saya pernah membuat grafik bar secara otomatis menggunakan library spreadsheet yang memotong sumbu Y di atas angka 0 secara otomatis. Hal tersebut membuat perbedaan performa yang sebenarnya sangat kecil dan tidak signifikan secara praktis nampak sangat dramatis, yang tanpa sengaja dapat menggiring audiens ke kesimpulan yang bias.

