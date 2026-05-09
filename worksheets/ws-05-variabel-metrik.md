# WS-05: Variabel & Metrik

> **Bab 5 — Metric, Measurement & Data**

---

## Ringkasan Materi

### Measurement Alignment Model

Setiap pengukuran yang valid harus bisa ditelusuri melalui rantai ini tanpa lompatan logis:

```
Problem → Concept → Variable → Metric → Data → Result
```

### Operationalization = Keputusan Desain

Menerjemahkan konsep abstrak menjadi variabel terukur bukan proses mekanis. "Code quality" yang diukur via SonarQube code smells membawa asumsi implisit. Setiap operasionalisasi harus didokumentasikan dan dijustifikasi.

### Empat Tipe Data (NOIR)

| Tipe | Ciri | Contoh | Operasi Valid |
|------|------|--------|---------------|
| **Nominal** | Kategori, tanpa urutan | Jenis algoritma (RF, SVM, CNN) | Modus, chi-square |
| **Ordinal** | Urutan, interval tidak sama | Skala Likert (1-5) | Median, Spearman |
| **Interval** | Jarak bermakna, tanpa nol absolut | Suhu Celsius | Mean, Pearson, t-test |
| **Ratio** | Jarak bermakna + nol absolut | Waktu eksekusi (ms) | Semua operasi |

Tipe data menentukan uji statistik yang valid. Kebanyakan metrik performa TI = ratio; persepsi pengguna = ordinal.

### Kriteria Pemilihan Metrik

- **Representative** — Mewakili konsep yang diteliti
- **Sensitive** — Cukup peka menangkap perbedaan bermakna (hindari ceiling effect)
- **Feasible** — Bisa dikumpulkan dalam batasan waktu dan biaya

### Pre-registration

Metrik harus ditentukan **sebelum** eksperimen. Memilih metrik setelah melihat data = **p-hacking**. Metrik tambahan yang ditemukan kemudian dilaporkan sebagai *exploratory*, bukan *confirmatory*.

### Primary vs Secondary Metric

- **Primary Metric** — Langsung terikat ke hipotesis, menentukan kesimpulan
- **Secondary Metric** — Pendukung, dilaporkan di samping primary; statusnya suplementer

### Research vs Engineering

| Aspek | Engineering | Research |
|-------|------------|----------|
| Pemilihan metrik | Berdasarkan kebiasaan/tool yang ada | Berdasarkan construct validity |
| Anomali | Dihapus untuk laporan bersih | Diinvestigasi — bisa jadi temuan |
| Kapan dipilih | Setelah sistem jadi (monitoring) | Sebelum eksperimen (by design) |

### Istilah Penting

- **Operationalization** — Transformasi konsep abstrak menjadi variabel terukur
- **Construct Validity** — Sejauh mana pengukuran benar-benar mengukur konsep yang dimaksud
- **Measurement Scale** — Klasifikasi data (NOIR) yang menentukan analisis valid
- **Multi-metric Evaluation** — Menggunakan beberapa metrik untuk menangkap konsep kompleks

---

## Template A.5 — Definisi Variabel, Metrik & Justifikasi

```
VARIABLE & METRIC DEFINITION

Research Question: ____________________

| Variabel | Tipe | Konsep | Metrik | Skala | Satuan | Cara Mengukur | Justifikasi |
|----------|------|--------|--------|-------|--------|---------------|-------------|
|          | IV   |        |        |       |        |               |             |
|          | DV   |        |        |       |        |               |             |
|          | CV   |        |        |       |        |               |             |

Alignment Check:
  RQ → Concept → Variable → Metric → Data → Result
  [ ] Setiap langkah terdokumentasi
  [ ] Tidak ada "lompatan logis"
  [ ] Metrik mengukur apa yang dimaksud (construct validity)
```

---

## Latihan 1 — Operationalization Chain

Gunakan RQ dari WS-04. Definisikan variabel dan metriknya.

**RQ:** Apakah Express.js menghasilkan Response Time, Throughput, dan Resource Usage yang signifikan berbeda dari Laravel pada REST API CRUD kompleks dengan 100K rows database dan 100 concurrent users?

| Variabel | Tipe | Konsep Abstrak | Metrik Konkret | Skala (NOIR) | Satuan |
|----------|------|---------------|----------------|-------------|--------|
| **Framework** | **IV** | Pendekatan runtime/engine server-side | Categorical: Laravel (PHP 8.2) vs Express.js (Node.js 18) | **Nominal** | — |
| **Response Time (Median)** | **DV** | Kecepatan server memproses request | Response time percentile 50 diukur k6 probe | **Ratio** | ms |
| **Throughput (RPS)** | **DV** | Kapasitas server handle requests per detik | (Successful requests / total duration) pada load steady-state | **Ratio** | req/sec |
| **Error Rate** | **DV** | Reliabilitas sistem | (Failed requests / total requests) × 100% | **Ratio** | % |
| **CPU Usage** | **DV** | Efisiensi resource CPU | Average CPU utilization Linux `top` selama test duration | **Ratio** | % |
| **Memory Usage** | **DV** | Efisiensi memory allocation | Peak RSS (Resident Set Size) server selama load test | **Ratio** | MB |
| **Response Time (p95)** | **DV** | Tail latency — worst-case user experience | Response time percentile 95 (95% requests lebih cepat) | **Ratio** | ms |
| **Database Size** | **CV** | Ukuran dataset | Konstant: 100,000 rows, 5 tables dengan foreign keys | **Ratio** | rows |
| **Load Profile** | **CV** | Karakteristik traffic | Konstant: k6 linear ramp-up 30 detik to 100 VU, sustain 2 min | **Nominal** | — |
| **Query Complexity** | **CV** | Business logic dalam request | Konstant: GET request multi-join 5 tables, filter 3+ fields, pagination | **Nominal** | — |

**Apakah ada lompatan logis dalam rantai?** [ ] Ya / [✓] Tidak
> Rantai logic RQ → IV (framework) → DV (6 metrics) → CV (databases, load, complexity) → Metrik Operasional (tool & method) semuanya jelas dan terkoneksi. Setiap DV directly related to IV (framework choice), dan CV controlled supaya comparison fair.

---

## Latihan 2 — Evaluasi Metrik

Evaluasi metrik DV yang dipilih di Latihan 1 menggunakan 3 kriteria.

| Kriteria | Skor (1-5) | Justifikasi |
|----------|-----------|-------------|
| **Representative** — Response Time (Median & p95) | **5** | Waktu respons adalah metrik paling fundamental—mengukur user experience langsung. Median mewakili typical user; p95 mewakili worst-case scenario. Keduanya essential untuk assessing quality of service. |
| **Representative** — Throughput (RPS) | **5** | Mengukur sistem capacity—directly related to scalability claim. Semakin tinggi RPS = semakin banyak concurrent users yang dilayani. |
| **Representative** — Error Rate | **4** | Reliabilitas systems penting, tapi tidak semua error adalah equal (HTTP 500 vs 429 rate-limit). Secondary metric untuk capture stability. |
| **Representative** — CPU/Memory Usage | **4** | Infrastructure cost efficiency. Operating expense penting tapi bukan primary user experience driver. Treated as secondary. |
| **Sensitive** — Response Time | **5** | k6 logs setiap request dengan presisi millisecond; histogram distribution mudah terlihat perbedaannya. Tidak ada ceiling/floor effect. |
| **Sensitive** — Throughput | **5** | Perbedaan kecil (5 RPS) mudah terdeteksi di aggregate data 100+ requests per detik. |
| **Sensitive** — Resource Usage | **3** | CPU/Memory baseline bisa berbeda per environment; relative comparison lebih penting. Jika baseline sudah dicontrol (CV), sensitivity meningkat. |
| **Feasible** — Semua DV | **5** | k6, Linux top, database query logs semua standard tools open-source, tidak perlu infrastructure mahal, bisa dijalankan dalam 5 menit per condition. |

**Apakah perlu secondary metric?** [✓] Ya / [ ] Tidak
> Jika ya, apa dan mengapa? 
> - **Secondary (confirming)**: Error Rate — memastikan throughput tinggi bukan karena framework melayani request invalid, but actual success
> - **Secondary (explanatory)**: Latency distribution (p50, p90, p99) — show tail behavior; helpful untuk analisis "mengapa Express.js lebih cepat?"
> - **Secondary (exploratory)**: Request breakdown by query type — understand which CRUD operations have biggest performance gap (CREATE vs READ vs UPDATE—helps identify bottleneck)

**Contoh kasus ceiling effect untuk metrik ini:**
> Response Time: Jika threshold beban test hanya 10 users (terlalu ringan), kedua framework bisa achieve <5ms response time → ceiling effect, tidak bisa bedakan. Solusi: set load pada production-realistic level (100 VU) sehingga metric bisa vary cukup untuk deteksi perbedaan.
> Throughput: Jika database query super fast (in-memory), RPS bisa maksimal (100 VU × 1 sec = 100 RPS fixed cap) → artificial limit. Solusi: include realistic query complexity (multi-join) untuk CPU menjadi bottleneck, bukan network/database.

---

## Latihan 3 — Data Quality Check

Bayangkan data yang akan dikumpulkan dari eksperimen. Evaluasi 4 dimensi kualitas data.

| Dimensi | Pertanyaan | Jawaban | Strategi Mitigasi |
|---------|-----------|---------|------------------|
| **Completeness** | Apakah semua data point terkumpul tanpa missing values? | Partial risk — k6 bisa corrupt output jika crash; MySQL slow log bisa truncate | (1) Setup monitoring redundant: capture k6 output ke file + database simultan; (2) Validate data count post-test: expect min 600 requests (100 VU × 2 min × 3 RPS typical) |
| **Consistency** | Apakah ada kontradiksi internal (e.g., sum throughput ≠ total requests)? | Low risk untuk metrics numerik; high risk untuk query logs (timing misalignment) | (1) Cross-validate k6 request count vs MySQL query log count; (2) Timestamp sync semua tools (NTP); (3) Log every request dengan unique ID trace |
| **Validity** | Apakah benar-benar mengukur yang dimaksud (construct validity)? | Medium risk — RT includes network latency (not pure server processing); Resource usage depends on monitoring tool precision | (1) Define clearly "Response Time" = server processing time excluding network (via k6 serverTime metric, not latency); (2) Validate tool calibration pre-test on reference server; (3) Document assumptions explicitly |
| **Representativeness** | Apakah sampel mewakili populasi target (real-world scenarios)? | High risk — 100K rows + simple pantry list ≠ real e-commerce (millions rows, complex business logic); 100 VU tidak mewakili spike traffic | (1) Define target use cases a priori (e-commerce product search, student management system queries, etc.); (2) Design dataset schema & query patterns mengikuti real data; (3) Load test include realistic usage patterns (not just uniform traffic); (4) Acknowledge scope limitation in conclusion |

---

## Refleksi

> Mengapa memilih metrik setelah melihat data dianggap p-hacking? Apa bedanya dengan eksplorasi data yang sah?

**Jawaban:**

**P-hacking (menolak**: Deciding metric OR significance threshold AFTER seeing results.
- Contoh p-hacking: (1) Jalankan eksperimen → lihat data → ternyata Response Time p-value=0.08 (tidak signifikan) → kemudian switch ke CPU usage sebagai primary metric → p-value=0.03 (signifikan!) → declare win
- Atau: (1) Jalankan eksperimen → lihat data → hitung semua possible metrics (RT, RPS, CPU, Memory, Error Rate, Latency p90, p95, p99) → report hanya yang signifikan (misalnya: 5 dari 15 metrics punya p<0.05 by chance alone)
- **Problem**: Dengan 15 hypothesis tests, expected false positive (alpha inflation) = 1 - (1-0.05)^15 ≈ 54%! Hasilnya **spurious finding**, bukan evidence.

**Eksplorasi data yang sah (allowed)**:
1. **Pre-registration**: Tentukan primary metric sebelum eksperimen (WS-05 ini IS pre-registration).
2. **Confirmatory Analysis**: Test primary metric against H₀/H₁
3. **Exploratory Analysis** (jika ada sisa eksperimen):
   - Analyze secondary/exploratory metrics
   - **Jelas label sebagai exploratory** (bukan confirmatory claim)
   - Report effect size + confidence intervals (bukan hanya p-value)
   - Gunakan less strict threshold (e.g., p<0.10 untuk exploratory vs p<0.05 untuk confirmatory)
   - Acknowledge: "Findings need replication in future study"

**Analogi**: 
- P-hacking = "Coba kunci pintu dengan 100 kunci acak → *satu* membuka → pretend kunci itu sudah ditargetkan dari awal"
- Legitimate Exploration = "Kunci master (primary metric) berhasil membuka → sekarang curious apakah juga bisa buka jendela (secondary) → tapi jelas-jelas acknowledge this is 'jendela' analysis, bukan inti"

**Implikasi untuk studi ini**:
- **Primary metrics** (pre-registered, WS-05): Response Time (median), Throughput, Error Rate → test H₀/H₁
- **Secondary metrics** (confirming): p95 latency, CPU/Memory → report supporting evidence
- **Exploratory** (if time): per-operation breakdown (CREATE vs READ response time diff) → label clearly, suggest for future study
- W**hen reporting**: "Response Time significantly different (H₁ supported, p=0.02, d=0.8); this confirms throughput difference (p=0.01); exploratory: CREATE operations 40% slower than READ (unexplained, needs investigation)"
