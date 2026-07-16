# WS-14: Analysis, Interpretation & Failure Analysis

> **Bab 14 — Analisis Data, Interpretasi & Failure Analysis**

---

## Ringkasan Materi

### Data → Knowledge Model

```
Data → Analysis → Interpretation → Explanation → Knowledge
```

Tiga level yang berbeda:
- **Analysis** — "Apa yang terjadi?" (deskriptif + inferensial)
- **Interpretation** — "Apa artinya?" (konteks RQ + literatur)
- **Failure Analysis** — "Mengapa tidak berhasil?" (boundary conditions)

### Beyond p-value

**Statistical significance ≠ practical significance.** Selalu laporkan:
1. p-value (signifikansi statistik)
2. Effect size (besarnya efek)
3. Confidence interval (rentang ketidakpastian)

| Effect Size (Cohen's d) | Interpretasi |
|-------------------------|-------------|
| < 0.2 | Small |
| 0.2 – 0.8 | Medium |
| > 0.8 | Large |

### Pemilihan Uji Statistik

| Kondisi | Uji yang Tepat |
|---------|---------------|
| 2 grup, normal, paired | Paired t-test |
| 2 grup, non-normal | Wilcoxon signed-rank |
| > 2 grup, normal | One-way ANOVA + post-hoc |
| > 2 grup, non-normal | Kruskal-Wallis + post-hoc |
| 2 variabel kontinu | Pearson (normal) / Spearman (rank) |

### Failure Analysis as Contribution

Hipotesis yang ditolak adalah **temuan yang berharga**:

| Dataset | New (F1) | Baseline (F1) | p-value | Cohen's d |
|---------|---------|--------------|---------|-----------|
| DS-1 (small, clean) | 94.2±1.1 | 89.3±1.5 | <0.001 | **3.7** |
| DS-4 (medium, noisy) | 78.3±3.2 | 82.1±2.8 | 0.008 | **-1.3** |
| DS-5 (large, noisy) | 71.6±4.1 | 80.5±3.0 | <0.001 | **-2.5** |

**Insight:** Metode baru unggul di data bersih tapi gagal di data noisy → asumsi Gaussian dilanggar → **boundary condition** ditemukan → hybrid approach direkomendasikan.

**Partial failure + deep analysis = kontribusi lebih kaya daripada full success tanpa analisis.**

### Limitation Types

| Jenis | Contoh |
|-------|--------|
| Internal validity | Confounders yang tidak dikontrol |
| External validity | Generalisasi ke domain lain |
| Construct validity | Metrik mengukur apa yang dimaksud? |
| Statistical limitation | Sample size, asumsi distribusi |

### Jebakan Kognitif

1. "Signifikan statistik = penting secara praktis" → cek effect size
2. "Hipotesis tidak didukung → cari sudut baru" → p-hacking
3. "Kegagalan tidak perlu dilaporkan detail" → missed insight
4. "Limitasi cukup disebutkan, tidak perlu dianalisis" → kedalaman hilang

---

## Template A.14 — Analysis & Interpretation Report

```
ANALYSIS & INTERPRETATION

1. Statistik Deskriptif:
   | Skenario | Mean | Std | Median | Min | Max | n |
   |----------|------|-----|--------|-----|-----|---|
   | Express.js Throughput (RPS) | 2.19 | 0.09 | 2.15 | 2.11 | 2.39 | 10 |
   | Laravel 11 Throughput (RPS) | 2.72 | 0.12 | 2.70 | 2.55 | 2.92 | 10 |
   | Express.js Avg Latency (ms) | 4295.00 | 191.62 | 3482.00 | 3930.00 | 4450.00 | 10 |
   | Laravel 11 Avg Latency (ms) | 3350.00 | 163.71 | 4069.00 | 3120.00 | 3620.00 | 10 |
   | Express.js p95 Latency (ms) | 9825.00 | 393.76 | 9825.00 | 9110.00 | 10480.00 | 10 |
   | Laravel 11 p95 Latency (ms) | 5832.00 | 385.22 | 5832.00 | 5300.00 | 6670.00 | 10 |

2. Uji Hipotesis:
   Uji yang digunakan  : Mann-Whitney U Test (RPS & Avg Latency) dan Welch's t-test (p95 Latency)
   Justifikasi          : Uji Shapiro-Wilk membuktikan bahwa metrik reqs_rps (p=0.0042) dan duration_avg (p=0.0046) pada Express.js melanggar asumsi normalitas (p < 0.05), sehingga memerlukan uji non-parametrik Mann-Whitney U. Sedangkan duration_p95 berdistribusi normal di kedua grup (Express p=0.78, Laravel p=0.58) sehingga diuji dengan Welch's t-test.
   Hasil:
   - Throughput (RPS)  : p = 0.00018 (p < 0.001), Cohen's d = -4.87 (Large Effect)
   - Avg Latency (ms)  : p = 0.00018 (p < 0.001), Cohen's d = 5.30 (Large Effect)
   - p95 Latency (ms)  : p = 9.13e-15 (p < 0.001), Cohen's d = 10.25 (Extreme Effect)
   CI 95%               :
   - Selisih RPS       : [-0.64, -0.43] RPS (Express vs Laravel)
   - Selisih Avg Lat   : [777.27, 1112.73] ms (Express vs Laravel)
   - Selisih p95 Lat   : [3627.01, 4358.99] ms (Express vs Laravel)

3. Keputusan:
   [x] H₀ ditolak → H₁ diterima (Terdapat perbedaan performa throughput dan latensi yang sangat signifikan antara Laravel 11 dan Express.js di bawah pembatasan resource ketat).
   [ ] H₀ tidak ditolak

4. Interpretasi:
   Hubungan ke RQ       : Laravel 11 menghasilkan throughput yang lebih tinggi (+24.22%), rata-rata latensi yang lebih rendah (-22.00%), dan p95 tail latency yang lebih stabil (-40.64%) dibanding Express.js secara signifikan di bawah pembatasan resource ketat.
   Practical significance: Selisih latensi rata-rata ~945 ms dan p95 ~3993 ms sangat berdampak pada User Experience. Di bawah beban saturasi, Laravel 11 jauh lebih responsif dan nyaman digunakan dibanding Express.js yang mengalami hambatan antrean callback.
   Perbandingan literatur: Bertentangan dengan anggapan populer bahwa Node.js selalu lebih cepat. Mendukung literatur modern yang menyatakan bahwa di bawah saturasi 1 CPU core dengan I/O database relasional berat, alokasi multi-process OS-scheduled milik PHP lebih resilien dibanding single-threaded event loop Node.js yang rentan antrean callback.

5. Limitation:
   | Jenis | Ancaman | Dampak | Mitigasi |
   |-------|---------|--------|----------|
   | External validity | PHP-CLI server dan Express HTTP bawaan | Kurang mewakili lingkungan produksi skala penuh (seperti Swoole / PM2 Cluster) | Ditulis sebagai batasan riset dan direkomendasikan pada Future Work |
   | Internal validity | Database MySQL dibatasi (2 CPU, 1GB RAM) | Latensi I/O database mendominasi waktu respons | MySQL dibatasi setara bagi kedua grup untuk menjamin keadilan perbandingan |
   | Statistical limitation | Sampel 10 runs per framework | Power test teoretis terbatas | Nilai p yang sangat kecil (<0.001) dan d > 4.0 memvalidasi kekuatan ukuran sampel 10 run |

6. Failure Analysis (jika H₀ tidak ditolak):
   Penyebab potensial  : Tidak berlaku (H₀ berhasil ditolak).
   Boundary condition   : Terbatas pada konkurensi sedang-tinggi (20 VUs) dengan query database multi-join kompleks pada 1.0 CPU Core. Pada beban ringan (1 VU) atau tanpa query DB berat, perbedaan performa kemungkinan menyusut.
   Insight              : Event loop Express.js sangat efisien untuk operasi I/O asinkron ringan, namun jika CPU jenuh akibat query relasional kompleks, antrean internal (starvation) akan melumpuhkan waktu respons.
```

---

## Latihan 1 — Pemilihan Uji Statistik

Tentukan uji statistik yang tepat untuk eksperimen Anda.

| Pertanyaan | Jawaban |
|-----------|---------|
| Berapa grup yang dibandingkan? | 2 grup (Express.js vs Laravel 11) |
| Apakah data berpasangan (paired)? | Tidak (Independent runs) |
| Apakah distribusi normal? (uji normalitas) | Tidak normal pada Express.js untuk `reqs_rps` (p=0.0042) dan `duration_avg` (p=0.0046); Normal pada Laravel 11 serta metrik `duration_p95` di kedua grup |
| **Uji yang dipilih:** | Mann-Whitney U Test (RPS & Avg Latency) dan Welch's t-test (p95 Latency) |
| **Justifikasi:** | Data Express.js melanggar asumsi normalitas pada Throughput dan Avg Latency, sehingga uji non-parametrik Mann-Whitney U yang bebas asumsi sebaran data lebih tepat. Untuk p95 Latency yang berdistribusi normal di kedua grup, digunakan Welch's t-test yang robust terhadap heteroskedastisitas. |

**Effect size yang akan dilaporkan:** [x] Cohen's d / [ ] Eta-squared / [ ] Lainnya: ____

---

## Latihan 2 — Interpretasi Hasil

Gunakan data berikut (atau data riil Anda) untuk berlatih interpretasi.

**Data:**
| Model | Accuracy (mean ± std) | n |
|-------|----------------------|---|
| A | 89.2 ± 1.5 | 10 |
| B | 87.8 ± 2.1 | 10 |

p = 0.045, Cohen's d = 0.74, CI 95% = [0.03, 2.77]

| Aspek | Interpretasi |
|-------|-------------|
| Signifikansi statistik | p = 0.045 < 0.05 → Perbedaan akurasi signifikan secara statistik pada α=0.05. |
| Effect size | d = 0.74 → Efek berukuran sedang ke besar (medium-to-large effect size). |
| Practical significance | Perbedaan akurasi rata-rata 1.4% dengan rentang keyakinan [0.03, 2.77]% tergolong marjinal di lingkungan praktis. Peningkatan ini mungkin tidak terlalu terasa kecuali pada sistem kritis yang memerlukan akurasi ekstrem. |
| Hubungan ke RQ | Model A memberikan performa klasifikasi yang lebih unggul dibanding Model B. Hipotesis keunggulan Model A didukung secara empiris. |
| Perbandingan literatur | Hasil ini mendukung tren literatur yang mengungguli Model A, namun besaran perbedaannya lebih rendah dibanding penelitian terdahulu yang mencatat selisih 3-5%, kemungkinan akibat dataset yang digunakan lebih menantang. |

---

## Latihan 3 — Failure Analysis

Latih kemampuan failure analysis: hipotesis TIDAK didukung. Apa yang bisa dipelajari?

**Skenario:** Metode baru Anda mendapat F1 = 83.2%, baseline = 84.7%. p = 0.12 (tidak signifikan).

| Pertanyaan | Jawaban |
|-----------|---------|
| Apakah ini "gagal"? | Bukan gagal total — hipotesis tidak terdukung adalah temuan yang valid dan menjadi kontribusi penting tentang batas efektivitas metode. |
| Kemungkinan penyebab? | Metode baru menambah beban overhead pemrosesan (+40% waktu komputasi) yang tidak sebanding dengan perolehan nilai F1 yang marjinal. |
| Boundary condition? | Metode ini hanya efisien jika dataset berukuran besar (≥ 10.000 record); pada dataset kecil (<1.000), algoritma baseline lebih stabil dan rendah overhead. |
| Insight yang bisa diambil? | Terdapat pertukaran (trade-off) yang jelas antara ukuran dataset dan kompleksitas model komputasi. Direkomendasikan metode hibrida yang adaptif. |
| Apakah layak dilaporkan? Mengapa? | Sangat layak. Melaporkan hasil negatif dan boundary condition mencegah pemborosan riset sejenis di masa depan dan dihargai tinggi di komunitas ilmiah. |

**Limitation terkait:**
| Jenis | Ancaman | Dampak |
|-------|---------|--------|
| *Contoh: Statistical* | *Contoh: Hanya 5 run per skenario* | *Power test rendah* |
| External validity | Dataset yang diuji hanya berasal dari satu domain homogen | Hasil tidak dapat digeneralisasi langsung pada domain data yang berbeda |
| Construct validity | F1-score mungkin tidak menggambarkan efisiensi konsumsi memori | Kesimpulan performa model menjadi terbatas pada akurasi saja |

---

## Refleksi

> Apakah "failure" dalam riset benar-benar gagal, atau justru kontribusi? Bagaimana failure analysis mengubah cara Anda melihat hasil negatif?

> Kegagalan pembuktian hipotesis (hasil negatif) bukanlah kegagalan ilmiah, melainkan penemuan penting mengenai *boundary conditions* (batas kondisi efektivitas) dari suatu metode. Mengetahui di mana dan mengapa suatu metode tidak bekerja memberikan kontribusi besar dengan membatasi ruang solusi teoretis dan mencegah peneliti lain melakukan duplikasi riset yang sia-sia.
>
> Failure analysis mengubah perspektif saya: hasil negatif tidak boleh disembunyikan atau dimanipulasi (*p-hacking*), melainkan harus didokumentasikan dan dianalisis secara jujur dan transparan. Dokumentasi kegagalan parsial yang mendalam sering kali memicu lahirnya ide pendekatan hibrida (*hybrid approach*) yang jauh lebih praktis di dunia nyata.
