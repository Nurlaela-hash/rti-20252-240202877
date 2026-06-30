# WS-10: Experiment Execution & Data Collection

> **Bab 10 — Eksekusi Eksperimen & Pengumpulan Data**

---

## Ringkasan Materi

### Experiment Execution Pipeline

```
Design → Execution Plan → Controlled Execution → Data Collection → Data Logging → Dataset for Analysis
```

### Multiple Run = Non-Negotiable

Single run **tidak pernah cukup** untuk klaim ilmiah. Minimum 5-10 run per skenario dengan seed berbeda. Multiple run menghasilkan:
- Mean, std, confidence interval
- Distribusi hasil → uji statistik
- Variabilitas → error bar di grafik

### Execution Plan

Setiap eksperimen harus memiliki plan sebelum eksekusi:
- Daftar skenario
- Jumlah run per skenario
- Random seed per run (pre-determined!)
- Urutan eksekusi (randomisasi/counterbalancing)
- Pre-execution checklist

### Data Logging Komprehensif

Setiap run menghasilkan log terstruktur:
1. **Identitas** — Run ID, timestamp, skenario
2. **Konfigurasi** — Semua parameter, seed, code version
3. **Hasil** — Semua metrik, output detail
4. **Metadata** — Waktu eksekusi, resource usage, warning/error

Format: CSV/JSON/database — **bukan stdout yang di-copy-paste**.

### Engineering vs Research Execution

| Aspek | Engineering | Research |
|-------|-----------|---------|
| Run | Sekali (deploy) | Multiple (min 5-10, seed berbeda) |
| Logging | Error log, access log | Semua parameter, metrik, metadata |
| Anomali | Bug → fix → redeploy | Investigasi → dokumentasi → analisis |
| Urutan | Tidak penting | Bisa bias — perlu randomisasi |

### Anomali = Dokumentasi, Bukan Hapus

Run gagal/anomali tidak boleh dihapus tanpa dokumentasi. Bisa jadi:
- **Bug** → fix & re-run (dokumentasikan!)
- **Batas kemampuan metode** → DNF = temuan
- **Data yang bias** jika hanya simpan run "berhasil"

### Jebakan Kognitif

1. "Satu angka cukup" → tanpa distribusi, tidak bisa diuji
2. "Seed tidak penting" → bahkan algoritma deterministik bisa dipengaruhi library stokastik
3. "Run gagal langsung hapus" → kehilangan temuan potensial
4. "Semua run harus hari ini" → thermal throttling, fatigue

---

## Template A.10 — Execution Plan & Data Log

```
EXECUTION PLAN

| Run # | Skenario | Seed | Parameter | Status | Waktu | Output File |
|-------|----------|------|-----------|--------|-------|-------------|
| 1-10  | Express.js CRUD | 42 | VU=20, Hold=20s | Selesai | 01:35 - 01:48 | run-express-*.txt |
| 11-20 | Laravel 11 CRUD | 42 | VU=20, Hold=20s | Selesai | 01:48 - 02:00 | run-laravel-*.txt |

Jumlah runs per skenario : 10
Total runs               : 20

DATA LOG (per run):
  Run ID    : run-laravel-10
  Timestamp : 2026-07-01T02:00:00+07:00
  Skenario  : Laravel 11 CRUD Kompleks
  Input     : 20 VUs, 100K data products, Warmup 30s, Sustain 20s
  Output    : RPS: 2.719, Avg Latency: 3350ms, Error Rate: 0.51%
  Anomali   : 1 request timeout (0.51% error rate) karena saturasi CPU host.
  Catatan   : Kontainer Laravel di-limit 1.0 CPU dan 512MB RAM.
```

---

## Latihan 1 — Execution Plan

Susun execution plan untuk eksperimen Anda. Tentukan skenario, jumlah run, dan seed sebelum eksekusi.

| Run # | Skenario | Seed | Parameter Kunci | Status |
|-------|----------|------|----------------|--------|
| 1 | Express.js CRUD | 42 | VU=20, Warmup=30s, Sustain=20s | Completed |
| 2 | Express.js CRUD | 42 | VU=20, Warmup=30s, Sustain=20s | Completed |
| 3 | Laravel 11 CRUD | 42 | VU=20, Warmup=30s, Sustain=20s | Completed |
| 4 | Laravel 11 CRUD | 42 | VU=20, Warmup=30s, Sustain=20s | Completed |
| 5 | Laravel 11 CRUD | 42 | VU=20, Warmup=30s, Sustain=20s | Completed |

**Total skenario:** 2 (Express.js vs Laravel 11)
**Run per skenario:** 10
**Total run keseluruhan:** 20


---

## Latihan 2 — Data Log Terstruktur

Desain format data log untuk eksperimen Anda. Tentukan field apa saja yang akan dicatat.

**Identitas:**
| Field | Contoh |
|-------|--------|
| Run ID | run-express-01 / run-laravel-01 |
| Timestamp | 2026-07-01T01:35:56+07:00 |
| Framework | Express / Laravel |

**Konfigurasi:**
| Field | Contoh |
|-------|--------|
| Seed | 42 |
| Code version | commit ffac99b (git hash) |
| Container Limits | cpus: '1.0', memory: 512M |

**Hasil:**
| Metrik | Tipe Data | Range Valid |
|--------|----------|-------------|
| *duration_avg* | *float (ms)* | *0.0 - 60000.0* |
| *duration_p95* | *float (ms)* | *0.0 - 60000.0* |
| *reqs_rps* | *float* | *0.0 - 1000.0* |
| *failed_pct* | *float (%)* | *0.0 - 100.0* |

**Format output:** [x] CSV / [x] JSON / [ ] Database / [x] Lainnya: Teks (UTF-16 k6 raw output)


---

## Latihan 3 — Anomaly Protocol

Rencanakan bagaimana menangani anomali. Untuk setiap jenis, tentukan langkah yang diambil.

| Jenis Anomali | Contoh | Tindakan |
|---------------|--------|----------|
| Run gagal (crash) | Database connection timeout / socket hangup | Dokumentasikan detail error, periksa / restart kontainer, bersihkan cache, jalankan ulang dan catat kejadian. |
| Hasil ekstrem | Throughput anjlok di bawah 0.5 RPS | Hentikan run, periksa utilitas memori/CPU host, pastikan tidak ada proses sinkronisasi awan atau update background OS. |
| Waktu eksekusi anomali | Latensi sangat tinggi pada detik-detik awal | Abaikan metrik detik-detik awal dengan memaksimalkan durasi warmup (warmup=30s) agar cache database dan engine JIT terisi. |
| Inkonsistensi dengan run lain | Timbul error rate minor pada Laravel run 10 | Dokumentasikan dan catat bahwa sistem mencapai batas saturasi CPU (100% cap) yang memicu kegagalan TCP handshaking sesaat. |


**Prinsip:** Detect → Investigate → Document → Decide

---

## Refleksi

> Pernahkah Anda melaporkan hasil riset/tugas dari single run? Apa risikonya? Bagaimana multiple run mengubah kepercayaan terhadap hasil?

**Pengalaman sebelumnya:**
Ya, dalam beberapa tugas kuliah terdahulu, sering kali pengujian hanya dijalankan satu kali untuk mengambil angka performa secara cepat. Risikonya adalah data tersebut sangat rentan terhadap distorsi fluktuasi sementara (seperti background process OS yang tiba-tiba aktif) sehingga angka yang diperoleh bisa menjadi pencilan (*outlier*) yang bias.

**Yang akan dilakukan berbeda:**
Menjalankan replikasi minimal 10 kali putaran secara teratur menggunakan seed jika diperlukan, serta menyajikan nilai agregat statistik deskriptif seperti nilai rata-rata (*mean*), median, persentil ke-95, serta standar deviasi. Hal ini meningkatkan validitas riset karena membuktikan stabilitas dan reproduktibilitas hasil pengukuran di bawah variabilitas lingkungan yang dinamis.

