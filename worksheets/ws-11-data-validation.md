# WS-11: Data Validation & Integrity

> **Bab 11 — Validasi Data & Integritas**

---

## Ringkasan Materi

### Data Trust Model

```
Raw Data → Data Cleaning → Consistency Check → Validation Process → Trusted Data
```

Data mentah belum bisa dipercaya. Harus melewati pipeline validasi sebelum siap untuk analisis statistik.

### Empat Pilar Data Quality

| Pilar | Deskripsi | Contoh Pelanggaran |
|-------|----------|-------------------|
| **Accuracy** | Nilai dalam range masuk akal | Akurasi = 1.5 (di luar [0,1]) |
| **Consistency** | Format seragam di semua run | Run 1: CSV, Run 2: JSON |
| **Completeness** | Tidak ada data hilang dari plan | 97 dari 100 run tercatat |
| **Validity** | Data sesuai desain eksperimen | Parameter baseline tercampur treatment |

### Proses Validasi Progresif

1. **Format validation** — Tipe file, header, kolom
2. **Range validation** — Nilai dalam batas logis
3. **Consistency validation** — Format seragam antar-run
4. **Logic validation** — Data cocok dengan desain eksperimen

Jika gagal di langkah awal → tidak perlu lanjut.

### Anomaly Detection — 3 Jenis

| Jenis | Deskripsi | Deteksi |
|-------|----------|---------|
| **Statistical outlier** | Nilai di luar distribusi normal | IQR: < Q1-1.5×IQR atau > Q3+1.5×IQR |
| **Contextual anomaly** | Normal absolut, abnormal dalam konteks | Run 1-10: ~91%, Run 11-20: ~88% |
| **Pattern anomaly** | Pola sistematis (bukan random) | Performa menurun berurutan |

**Prinsip:** Detect → Investigate → Document → Decide — **JANGAN langsung hapus.**

### Engineering vs Research Validation

| Aspek | Engineering | Research |
|-------|-----------|---------|
| Tujuan | Data sesuai spesifikasi bisnis | Data layak untuk analisis statistik |
| Missing data | Impute / set default | Investigasi penyebab → dokumentasi |
| Outlier | Bug → fix | Mungkin temuan → investigasi |
| Dokumentasi | Minimal (log error) | Komprehensif (anomali + keputusan) |

### Jebakan Kognitif

1. "Logging otomatis ≠ data benar" → bisa ada bug di logger
2. "Outlier = hapus" → bisa jadi temuan penting
3. "Dataset kecil tidak perlu validasi" → justru lebih rentan
4. "Mean normal = data benar" → [94, 95, 93, **44**, 94] → mean 84% terlihat wajar

---

## Template A.11 — Data Validation Checklist

```
DATA VALIDATION CHECKLIST

Completeness:
  [x] Semua skenario tercakup
  [x] Jumlah run sesuai rencana
  [x] Tidak ada file output hilang
  Missing: 0 dari 20 data points

Format Consistency:
  [x] Semua file format sama (CSV/JSON/...)
  [x] Header konsisten
  [x] Tipe data konsisten (numerik tetap numerik)

Range & Logic:
  [x] Nilai dalam range masuk akal
  [x] Tidak ada waktu negatif
  [x] Metrik 0–100%, tidak di luar range
  Anomali ditemukan: Laravel Run 10 mencatat 0.51% error rate (masih di bawah ambang batas toleransi).

Cross-Validation:
  [x] Run identik → hasil mendekati
  [x] Trend konsisten dengan ekspektasi teori

Keputusan:
  [x] Data siap analisis
  [ ] Perlu cleaning
  [ ] Perlu re-run (skenario: —)
```

---

## Latihan 1 — Completeness Check

Verifikasi apakah semua data yang direncanakan sudah terkumpul.

| Skenario | Run Direncanakan | Run Tercatat | Missing | Alasan |
|----------|-----------------|-------------|---------|--------|
| Express.js CRUD | 10 | 10 | 0 | — |
| Laravel 11 CRUD | 10 | 10 | 0 | — |

**Total expected:** 20 | **Total actual:** 20 | **Missing:** 0

**Keputusan untuk data missing:**
Tidak ada data yang hilang. Seluruh 20 run dari kedua skenario berhasil diselesaikan dan dicatat secara utuh ke dalam folder log riset.


---

## Latihan 2 — Anomaly Investigation

Periksa data Anda untuk anomali. Gunakan metode IQR atau z-score.

**Dataset sampel (atau data Anda sendiri):**
(Menggunakan metrik Error Rate (%) pada skenario Laravel 11)

| Run | Error Rate (%) |
|-----|-------------|
| 1-9 | 0.00 |
| 10  | 0.51 |

**Deteksi outlier:**
- Q1 = 0.00% | Q3 = 0.00% | IQR = 0.00%
- Batas bawah (Q1 - 1.5×IQR) = 0.00%
- Batas atas (Q3 + 1.5×IQR) = 0.00%
- Outlier terdeteksi: Run 10 (0.51% > Batas Atas 0.00%)

**Investigasi (untuk setiap outlier):**

| Outlier | Nilai | Kemungkinan Penyebab | Keputusan |
|---------|-------|---------------------|-----------|
| Run 10 | 0.51% | Saturasi CPU host penuh (100% core cap) di akhir pengujian k6, memicu overhead TCP connection timeout sesaat pada web server PHP-CLI. | Dokumentasikan kejadian ini sebagai batas kapasitas reliabilitas backend Laravel di bawah limitasi resource ketat, data tetap disimpan karena valid merepresentasikan beban kritis. |


---

## Latihan 3 — Validation Report

Buat laporan validasi ringkas untuk dataset eksperimen Anda.

**1. Completeness:** 100% data terkumpul
**2. Format:** [x] Konsisten / [ ] Ada inkonsistensi: —
**3. Range check (anomali):** Ditemukan satu outlier minor (0.51% error rate pada Laravel run 10) tetapi masih berada dalam batas logis (nilai positif dan di bawah ambang batas toleransi kegagalan 5.00%).
**4. Logic check:** [x] Parameter sesuai plan / [ ] Ada ketidaksesuaian: —

**Kesimpulan:** [x] Data siap analisis / [ ] Perlu tindakan: —


---

## Refleksi

> Apa perbedaan antara "data yang benar" dan "data yang dipercaya"? Mengapa proses validasi formal diperlukan meskipun data dikumpulkan secara otomatis?

"Data yang benar" adalah data mentah hasil pembacaan sensor atau logger apa adanya yang merekam fakta fisik (bisa jadi corrupt, terdistorsi warm-up, atau bias). "Data yang dipercaya" adalah data yang sudah divalidasi kelengkapannya, diuji konsistensinya, dan diinvestigasi anomali/pencilannya sehingga terjamin bersih, adil, dan valid secara statistik untuk ditarik kesimpulan ilmiah.

Proses validasi formal tetap diperlukan karena automasi logging tidak menjamin kualitas data. Logger otomatis tidak mengetahui bias kontekstual (seperti thermal throttling host atau transient network lag) yang mendistorsi angka performa. Validasi formal membantu peneliti mengidentifikasi dan mendokumentasikan anomali agar tidak salah menarik simpulan.

