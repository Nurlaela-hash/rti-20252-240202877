# WS-13: Data Preprocessing

> **Bab 13 — Preprocessing & Persiapan Data untuk Analisis**

---

## Ringkasan Materi

### Data Refinement Pipeline

```
Raw Data → Cleaning → Transformation → Normalization → Processed Data → Analysis Ready
```

Setiap tahap memiliki tujuan berbeda. **Preprocessing bukan langkah teknis biasa** — setiap keputusan preprocessing adalah keputusan riset yang bisa mengubah kesimpulan.

### Empat Prinsip Preprocessing

| Prinsip | Deskripsi |
|---------|----------|
| **Consistency** | Metode sama untuk data yang sama |
| **Transparency** | Setiap langkah terdokumentasi |
| **Reproducibility** | Orang lain bisa mengulang dengan hasil sama |
| **Minimal Distortion** | Ubah sesedikit mungkin; jika normalisasi tidak perlu, jangan lakukan |

### Cleaning Triad

| Masalah | Strategi | Risiko |
|---------|---------|--------|
| **Missing values** | | |
| — Listwise deletion | Missing < 5%, random | Data loss |
| — Mean/median imputation | Sedikit missing, dist. normal | Mengurangi variabilitas |
| — Model-based imputation | Banyak missing, pola sistematis | Introduces dependency |
| — Flag & separate | Missing karena alasan substantif | Kompleksitas analisis |
| **Duplikat** | Identifikasi → verifikasi → hapus | False positive (data mirip ≠ duplikat) |
| **Error format** | Standardisasi tipe, encoding | Kehilangan informasi saat konversi |

### Normalisasi — Kapan & Metode Mana

| Metode | Formula | Output | Sensitif Outlier? |
|--------|---------|--------|-------------------|
| Min-max | (x-min)/(max-min) | [0, 1] | Ya |
| Z-score | (x-mean)/std | Unbounded | Lebih robust |
| Robust scaling | (x-median)/IQR | Unbounded | Paling robust |

**Kunci:** Parameter normalisasi harus dihitung dari **training set saja** — bukan seluruh data. Pelanggaran = **data leakage**.

### Data Leakage Prevention

Data leakage terjadi ketika informasi dari test set "bocor" ke preprocessing:
- Normalisasi parameter dari seluruh dataset ← **SALAH**
- Cross-validation dilakukan sebelum split ← **SALAH**
- Feature selection menggunakan label test set ← **SALAH**

### Jebakan Kognitif

1. "Preprocessing cuma teknis — tidak perlu detail" → bisa ubah kesimpulan
2. "Lebih banyak preprocessing = lebih bersih = lebih baik" → over-processing distorsi data
3. "Normalisasi selalu diperlukan" → belum tentu, tergantung metode analisis
4. "Imputation sama untuk semua situasi" → strategi harus sesuai konteks

---

## Template A.13 — Preprocessing Documentation Log

```
PREPROCESSING LOG

Dataset           : k6 benchmark execution logs (run-express-*.txt, run-laravel-*.txt)
Jumlah data awal  : 20 raw text log files (10 Express, 10 Laravel)

Cleaning:
| Masalah | Jumlah Kasus | Penanganan | Justifikasi |
|---------|-------------|------------|-------------|
| Missing | 0 kasus      | N/A        | Semua file log lengkap sesuai dengan 10 run per skenario |
| Duplikat| 0 kasus      | N/A        | File log diberi nama terstruktur (run-framework-num.txt) |
| Error   | 20 kasus     | Decoding multi-encoding (UTF-16-LE/BE, UTF-8) + filter baris target + standardisasi unit waktu ke milidetik (ms) | Log PowerShell terindikasi UTF-16 dengan BOM; satuan waktu k6 bervariasi (s, ms, µs) dan perlu diseragamkan |

Transformation:
| Transformasi | Variabel | Detail | Alasan |
|-------------|----------|--------|--------|
| Regex Parsing | duration_avg, duration_med, duration_p95, reqs_rps, reqs_total, failed_pct | Ekstraksi angka numerik dari log tekstual k6 menggunakan ekspresi reguler | Mengubah data log tekstual tidak terstruktur menjadi data terstruktur (dictionary) |
| Aggregation   | Semua metrik di atas | Perhitungan nilai rata-rata (mean) dan standar deviasi (std) dari 10 runs | Menyediakan statistik deskriptif ringkasan yang representatif untuk perbandingan |

Normalization:
  Metode    : Tidak dilakukan (No normalization)
  Alasan    : Metrik latensi (ms) dan throughput (RPS) memiliki arti fisik langsung yang penting bagi interpretasi performa web server. Normalisasi akan mendistorsi makna praktis metrik dan tidak diperlukan karena riset tidak menggunakan model ML berbasis jarak.
  Parameter : (dihitung dari: Tidak berlaku / bukan machine learning)

Leakage Check:
  [x] Parameter normalisasi dari training set saja (tidak ada data normalization lintas framework)
  [x] Tidak ada informasi test set dalam preprocessing (analisis dilakukan independen per-framework)
  [x] Cross-validation dilakukan setelah split (analisis statistik didasarkan pada run independen terpisah)

Jumlah data akhir : 20 records data terstruktur (10 run Express, 10 run Laravel) dengan 6 metrik kunci
Script tersedia   : [x] Ya → path: riset-directory/06-output/parse_results.py | [ ] Belum
```

---

## Latihan 1 — Cleaning Plan

Periksa dataset Anda (atau dataset contoh) dan dokumentasikan masalah yang ditemukan.

| Masalah | Jumlah Kasus | Penanganan | Justifikasi |
|---------|-------------|------------|-------------|
| *Contoh: Missing di kolom "label"* | *12 dari 500 (2.4%)* | *Listwise deletion* | *< 5%, distribusi random (MCAR)* |
| Missing values | 0 dari 20 kasus (0.0%) | N/A | Seluruh file log lengkap dan berhasil diperoleh dari 10 run per framework |
| Duplikat data | 0 dari 20 kasus (0.0%) | N/A | File log diidentifikasi secara unik berdasarkan penamaan terstruktur |
| Encoding & Unit Mismatch | 20 dari 20 kasus (100%) | Dekode sekuensial (UTF-16-LE/BE, UTF-8) + Standardisasi unit waktu ke milidetik (ms) | Log PowerShell menggunakan UTF-16; metrik k6 menggunakan satuan dinamis (s, ms, µs) yang harus diseragamkan ke ms |

**Jumlah data sebelum cleaning:** 20 files log mentah (~370 KB data teks tidak terstruktur)
**Jumlah data setelah cleaning:** 20 records data terstruktur (10 run Express, 10 run Laravel)
**Persentase data yang hilang/berubah:** 0.0% data hilang, 100% data ditransformasikan ke format terstruktur

---

## Latihan 2 — Normalisasi Decision

Tentukan apakah data Anda perlu normalisasi, dan jika ya, metode apa yang tepat.

| Variabel | Range Asli | Distribusi | Outlier? | Metode Normalisasi | Alasan |
|----------|-----------|-----------|----------|-------------------|--------|
| *Contoh: response_time* | *0.1 – 45.2s* | *Right-skewed* | *Ya (45.2s)* | *Robust scaling* | *Ada outlier, perlu robust* |
| *Contoh: accuracy_score* | *0.72 – 0.95* | *Normal, narrow* | *Tidak* | *Tidak perlu* | *Sudah dalam [0,1], metode berbasis distance tidak digunakan* |
| `duration_avg` / `duration_p95` | 3120 ms – 10480 ms | Right-skewed di bawah beban saturasi | Tidak ada outlier ekstrem (variasi wajar under-load) | Tidak perlu | Nilai latensi harus dilaporkan dalam satuan fisik asli (ms) agar memiliki makna praktis industri, dan riset tidak menggunakan model ML berbasis jarak |
| `reqs_rps` | 2.111 – 2.919 req/s | Normal / Narrow | Tidak | Tidak perlu | Throughput (RPS) memiliki makna industri langsung yang akan kabur jika dinormalisasi |
| `failed_pct` | 0.00% – 0.51% | Highly Skewed (mayoritas 0%) | Ya (Laravel Run 10 = 0.51%) | Tidak perlu | Persentase error rate harus dianalisis apa adanya untuk memverifikasi batas toleransi validitas eksperimen (<5%) |

**Apakah normalisasi diperlukan?** [ ] Ya / [x] Tidak
**Justifikasi:**
> Seluruh metrik pengujian performa (latensi, throughput, dan tingkat kesalahan) dalam riset ini disajikan dalam satuan fisik asli (milidetik, req/s, dan %) agar dapat langsung diinterpretasikan secara praktis oleh praktisi backend. Normalisasi (seperti Min-Max atau Z-Score) tidak diperlukan karena penelitian ini berfokus pada analisis komparatif deskriptif empiris dan uji hipotesis statistik, bukan pelatihan model machine learning berbasis jarak (seperti k-NN atau SVM) yang sensitif terhadap perbedaan skala fitur.

**Leakage check:**
- [x] Parameter dihitung dari training set saja (tidak ada pencampuran metrik lintas framework)
- [x] Normalisasi diterapkan setelah train-test split (tidak ada normalisasi lintas kelompok)

---

## Latihan 3 — Preprocessing Report

Buat ringkasan preprocessing lengkap — dokumentasi yang cukup bagi orang lain untuk mereplikasi.

```
PREPROCESSING SUMMARY

1. Dataset: k6 benchmark execution logs (run-express-*.txt, run-laravel-*.txt)
2. Data awal: 20 records (files), ~120 lines per file
3. Cleaning:
   - Missing values: 0 kasus, metode: N/A (semua 20 run lengkap)
   - Duplikat: 0 kasus, tindakan: N/A
   - Error: 20 kasus (encoding UTF-16 dan mismatch unit waktu k6), tindakan: multi-encoding decoding (try utf-16-le, utf-16-be, utf-8) + unit time normalization ke milidetik (ms)
4. Transformation: Regex parsing untuk mengekstrak metrik numerik + agregasi statistik (mean, std dev, min, max) lintas 10 runs per framework
5. Normalisasi: Tidak dilakukan (No normalization) karena mempertahankan unit fisik asli metrik
6. Data akhir: 20 records data terstruktur (10 run Express, 10 run Laravel), 6 features (metrik)
7. Leakage check: [x] Lulus / [ ] Ada masalah
```

---

## Refleksi

> Apakah Anda pernah melakukan normalisasi "karena biasa dilakukan" tanpa mempertimbangkan apakah benar-benar diperlukan? Apa risiko over-preprocessing?

> Ya, dalam pengerjaan proyek analitik data atau machine learning sebelumnya, normalisasi (seperti MinMaxScaler atau StandardScaler) sering kali diterapkan secara otomatis sebagai "langkah standar wajib" tanpa menganalisis jenis algoritma yang digunakan atau kegunaan metrik tersebut. Sebagai contoh, algoritma berbasis pohon keputusan (Decision Trees/Random Forest) sebenarnya tidak memerlukan normalisasi, namun tetap dinormalisasi karena kebiasaan.
>
> Risiko over-preprocessing meliputi:
> 1. **Kehilangan Interpretabilitas**: Data kehilangan satuan fisik aslinya (misalnya, 3350 ms berubah menjadi 0.12 atau -0.85), sehingga menyulitkan pemahaman praktis hasil analisis secara intuitif.
> 2. **Distorsi Informasi (Minimal Distortion Violation)**: Pengolahan yang berlebihan dapat mengubah bentuk distribusi asli atau malah memperbesar noise/outlier minor secara tidak proporsional.
> 3. **Data Leakage**: Melakukan fit scaling pada seluruh dataset sebelum split secara tidak sengaja membocorkan informasi pengujian ke data pelatihan, yang berakibat pada bias evaluasi model.
