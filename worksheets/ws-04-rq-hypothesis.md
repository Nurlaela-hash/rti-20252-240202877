# WS-04: Research Question & Hypothesis

> **Bab 4 — Research Question, Contribution & Hypothesis**

---

## Ringkasan Materi

### RQ Bukan Pertanyaan Biasa

Research Question yang baik secara implisit mengandung cetak biru eksperimen: subjek, baseline, metrik, domain, dataset.

| Kualitas | Contoh |
|----------|--------|
| **Buruk** | "Bagaimana pengaruh deep learning terhadap deteksi malware?" |
| **Baik** | "Apakah CNN menghasilkan F1-Score lebih tinggi dari RF pada CIC-MalMem-2022?" |

Perbedaan: RQ yang baik menyebutkan **metode spesifik**, **metrik terukur**, **baseline**, dan **dataset**.

### Tiga Jenis RQ

| Jenis | Pola | Kebutuhan |
|-------|------|-----------|
| **Comparison** | A vs B → mana lebih baik? | ≥ 2 metode, metrik sama |
| **Improvement** | A' vs A → modifikasi lebih baik? | Pre/post, bukti perbaikan |
| **Exploratory** | Faktor X₁...Xₙ → pengaruh terhadap Y? | Multi-variabel, korelasi/regresi |

### Contribution Statement

Tiga jenis kontribusi: **Improvement** (metode terbukti lebih baik), **Comparison** (perbandingan sistematis yang belum ada), **Novel Approach** (pendekatan baru). Kontribusi harus terhubung langsung dengan gap — kontribusi tanpa gap = klaim tanpa justifikasi.

### Hypothesis H₀ / H₁

- **H₀** (Null) = Tidak ada perbedaan signifikan — asumsi default, harus dibuktikan salah
- **H₁** (Alternative) = Ada perbedaan signifikan — diterima hanya jika H₀ ditolak
- Harus **falsifiable**, mengandung **metrik terukur**, dirumuskan **SEBELUM eksperimen**

### Rantai Operasionalisasi

```
RQ → Variable → Metric → Data → Analysis
```

Jika rantai ini tidak lengkap, RQ belum mature. Bi-directional: RQ yang tidak bisa jadi hipotesis testable harus direvisi mundur.

### Research vs Engineering

| Aspek | Engineering | Research |
|-------|------------|----------|
| Tujuan pertanyaan | Apa yang harus dibangun? | Apa yang harus dibuktikan? |
| Bentuk jawaban | Sistem yang berfungsi | Bukti empiris terukur |
| Sukses diukur oleh | User satisfaction, uptime | Signifikansi statistik, effect size |
| Jika gagal | Debug dan perbaiki | Laporkan, analisis mengapa |

### Istilah Penting

- **Research Question (RQ)** — Pertanyaan spesifik: variabel terukur + metrik + konteks
- **Contribution Statement** — Apa yang diketahui setelah riset selesai yang sebelumnya belum ada
- **H₀ / H₁** — Null vs Alternative Hypothesis
- **Falsifiability** — Kondisi hipotesis ditolak harus bisa didefinisikan sebelum eksperimen
- **Operationalization** — Proses mewujudkan konsep abstrak menjadi variabel terukur

---

## Template A.4 — RQ-Contribution-Hypothesis

```
RQ-CONTRIBUTION-HYPOTHESIS

Gap Statement  : Performa Laravel vs Express.js pada REST API dengan transactional complexity tinggi, realistic database scale, dan production-ready requirements masih belum diteliti secara komprehensif.

Research Question:
  Tipe         : [x] Comparison  [ ] Improvement  [ ] Exploratory
  Formulasi    : Apakah Express.js menghasilkan Response Time, Throughput, dan Resource Usage yang signifikan berbeda dari Laravel pada REST API CRUD kompleks dengan database 100K+ rows dan 100 concurrent users?
  Variabel IV  : Framework backend (Laravel vs Express.js)
  Variabel DV  : Response Time, Throughput, Error Rate, CPU Usage, Memory Usage
  Metrik       : Median RT, p95 RT, RPS, error rate, CPU %, memory MB
  Dataset      : MySQL 100K+ rows, 5 tables, CRUD kompleks dengan join dan pagination
  Baseline     : Laravel 11 + Eloquent vs Express.js 4.x + Sequelize

Quality Check RQ:
  [x] Variabel spesifik
  [x] Metrik jelas
  [x] Baseline ada
  [x] Konteks disebutkan
  [x] Memerlukan eksperimen (bukan hanya survei literatur)

Contribution Statement:
  Apa yang baru diketahui : Perbandingan sistematis pada skala realistis dengan transactional complexity dan production-ready requirements
  Jenis kontribusi        : [ ] Improvement  [x] Comparison  [ ] Novel approach
  Gap yang diisi          : Performance gap, data gap, dan context gap pada studi Laravel vs Express.js sebelumnya

Hypothesis Pair:
  H₀ : Tidak ada perbedaan signifikan pada Response Time, Throughput, atau Resource Usage antara Express.js dan Laravel
  H₁ : Ada perbedaan signifikan minimal pada satu metrik antara Express.js dan Laravel
  Threshold              : p-value < 0.05 dan/atau perbedaan praktis ≥15% pada metrik utama
  Justifikasi threshold  : 0.05 adalah standar statistik akademik; 15% dianggap perbedaan praktis yang bermakna untuk user experience
```

---

## Latihan 1 — Dari Gap ke RQ

Gunakan gap yang ditemukan di WS-03. Transformasikan menjadi Research Question.

**Gap dari WS-03:** 
Performa Laravel vs Express.js pada REST API dengan transactional complexity tinggi, realistic database scale (100K+ rows), dan production-ready requirements (authentication, caching, async operations) masih belum diteliti secara komprehensif. Literature menunjukkan studi sebelumnya hanya evaluasi pada data dummy (50 rows max) dengan skenario simple CRUD tanpa complex business logic.

**RQ versi pertama (tulis bebas):**
> Bagaimana perbandingan performa Laravel dan Express.js ketika digunakan untuk membangun REST API dengan business logic kompleks dan database skala enterprise?

**Evaluasi RQ:**

| Komponen | Ada? | Isi |
|----------|------|-----|
| Metode spesifik | Ya | Laravel (PHP + Eloquent ORM) vs Express.js (Node.js + Sequelize ORM) |
| Metrik terukur | Ya | Response Time, Throughput (RPS), CPU Usage, Memory Usage, Error Rate |
| Baseline | Ya | Kedua framework adalah state-of-the-art di industri (Stack Overflow top choices) |
| Dataset/konteks | Ya | Realistic database (100K+ rows), REST API CRUD kompleks (multi-join, filter, pagination) |

**Tipe RQ:** [✓] Comparison / [ ] Improvement / [ ] Exploratory

**RQ versi revisi (setelah evaluasi):**
> **RQ: Apakah Express.js menghasilkan Response Time, Throughput, dan Resource Usage (CPU/Memory) yang signifikan berbeda dari Laravel ketika menjalankan REST API CRUD kompleks dengan database 100K+ rows dan load 100 concurrent virtual users?**

---

## Latihan 2 — Hypothesis Pair

Rumuskan pasangan hipotesis dari RQ di Latihan 1.

| Komponen | Isi |
|----------|-----|
| H₀ | Tidak ada perbedaan signifikan pada Response Time, Throughput, atau Resource Usage antara Express.js dan Laravel ketika menjalankan REST API CRUD kompleks dengan database 100K rows pada load 100 concurrent users |
| H₁ | Ada perbedaan signifikan minimal pada satu metrik (Response Time, Throughput, atau Resource Usage) antara Express.js dan Laravel pada kondisi pengujian yang sama |
| Metrik Utama | Response Time (median & p95), Throughput (RPS success rate), CPU Usage (%), Memory Usage (MB) |
| Threshold Signifikansi | Relatif: Perbedaan ≥15% pada Response Time atau Throughput; atau p-value < 0.05 pada statistical test (Mann-Whitney U test untuk non-normal distribution) |
| Justifikasi threshold | 15% adalah nilai practical significance di industri — perbedaan <15% sering dianggap "negligible" untuk user experience; statistical p-value <0.05 adalah standard akademis (α=0.05). Kedua threshold used together untuk holistic evaluation |

**Apakah hipotesis ini falsifiable?** [✓] Ya / [ ] Tidak
> Cara membuktikannya salah: 
> - H₀ ditolak jika: Menjalankan 3 kali eksperimen → setiap metrik (Response Time, Throughput, CPU/Memory) menunjukkan perbedaan ≥15% konsisten antara Express.js dan Laravel, DAN p-value < 0.05 pada statistical test
> - H₁ ditolak (H₀ diterima) jika: Semua hasil pengujian menunjukkan perbedaan <15% ATAU p-value > 0.05, berarti tidak ada bukti kuat perbedaan performa

---

## Latihan 3 — Rantai Operasionalisasi

Lengkapi rantai dari RQ hingga metode analisis.

| Tahap | Isi |
|-------|-----|
| RQ | Apakah Express.js menghasilkan Response Time, Throughput, dan Resource Usage yang signifikan berbeda dari Laravel pada REST API CRUD kompleks dengan 100K rows database dan 100 concurrent users? |
| Variable (IV) — Independent | Framework backend: Laravel (PHP 8.2 + Eloquent ORM + Apache) vs Express.js (Node.js v18 + Sequelize ORM) |
| Variable (DV) — Dependent | 1) Response Time (median & p95 percentile) 2) Throughput (successful RPS) 3) Error Rate (%) 4) CPU Usage (%) 5) Memory Usage (MB) |
| Variable (CV) — Control | Database: MySQL 5.7 sama (100K rows, 5 tables joined), Request: HTTP GET/POST identik, Load profile: k6.io 100 VU ramp-up 30s, Payload size: identik, Caching: disabled untuk fair comparison |
| Metric Operasionalization | Response Time = server processing time (ms) measured by k6 probe; Throughput = (successful requests / total time) in RPS; CPU = Linux `top` average %; Memory = peak RAM usage (MB) |
| Data source | k6 performance testing tool, OS metrics (top, vmstat), MySQL slow query log |
| Analysis method | Descriptive statistics (mean, median, p95), Visual comparison (line charts), Mann-Whitney U test (non-parametric, since RT distribution likely non-normal), Effect size (Cohen's d) |

**Apakah rantai lengkap?** [✓] Ya / [ ] Tidak
> Rantai sudah lengkap dan terkoneksi: RQ → IV (framework) → DV (performance metrics) → Metric (operationalization konkret) → Data (tool & method) → Analysis (statistical test). Setiap tahap bisa ditelusuri logis tanpa lompatan.

---

## Contribution Statement

| Elemen | Isi |
|--------|-----|
| Apa yang sekarang diketahui (dari WS-03) | Laravel vs Express.js sudah dibandingkan di beberapa paper, tapi pada data dummy (50 rows max) dan skenario simple CRUD tanpa transaksi kompleks |
| Apa yang baru diketahui setelah riset | **Systematic perbandingan pada realistic scale** (100K+ rows) dengan **transactional complexity** (multi-join, complex filter, pagination) dan **production-ready load** (100 concurrent users), dengan comprehensive metrics (not just response time; also CPU/Memory/Error Rate) |
| Jenis kontribusi | [✓] Comparison (systematic vs existing studies yang partial) + [✓] Context (explicit transfer to Indonesian enterprise context — e-commerce, manajemen SDM, sistem akademik) |
| Gap yang diisi | Performance Gap (realistic scale), Data Gap (large dataset), Context Gap (production requirements like pagination, complex queries, error handling) |

**Research Position (explicit statement terhadap WS-03):**
> Berbeda dari Siahaan & Wijaya (2024) dan Mosul et al. (JITET) yang test pada single-operation simple CRUD dengan 50 rows, riset ini mengisi gap dengan: (1) realistic database enterprise scale (100K rows—mimics production e-commerce catalog); (2) business logic kompleks (multi-table join, filtering on 5+ fields, pagination, soft delete); (3) production load profile (concurrent users ramp-up, sustained load testing). Kontribusi: framework selection guidance yang actionable untuk Indonesian engineers di konteks real-world scenarios, bukan theoretical comparisons.

---

## Refleksi

> Ambil satu judul skripsi/paper yang pernah dibaca. Coba ekstrak RQ-nya. Apakah RQ tersebut memenuhi semua komponen (metode, metrik, baseline, konteks)? Jika tidak, apa yang hilang?

**Judul:** "Performance Comparison Between Laravel and ExpressJs Framework Using Apache JMeter" (Siahaan & Wijaya, 2024)

**RQ yang diekstrak:** 
Pada paper ini, RQ implicit (tidak ditulis eksplisit): "Mana yang lebih cepat antara Laravel dan Express.js?"

**Komponen yang hilang:**
1. ✓ **Metode**: Ada (Laravel vs Express.js) — spesifik
2. ✓ **Metrik**: Ada (response time, throughput) — clear
3. ✓ **Baseline**: Ada (kedua framework state-of-the-art) — fair
4. **Konteks**: **Partial** — paper mention "MySQL database" tapi tidak eksplisit tentang data scale (berapa rows?), query complexity (simple CRUD vs aggregate?), load profile (berapa concurrent users exactly?)
5. **Threshold/Acceptance**: **MISSING** — paper show hasil numerik tapi tidak define "kapan perbedaan dianggap signifikan?" (15%? 50%?)

**Gap kritisnya:**
Paper Siahaan (2024) menggunakan "student data access" tapi tidak jelas: berapa students di database? Apakah sudah full CRUD dengan join tables? Hasil menunjukkan Laravel: 1745.7 ms vs Express.js faster, tapi tanpa statistical testing — bisa jadi perbedaan itu **random variation** atau **true difference**?

**Kesimpulan:** RQ di Siahaan paper sudah "lebih baik dari rata-rata," tapi masih bisa diperkuat dengan:
- Explicit threshold ("Express.js ==  dianggap 'significantly faster' jika response time ≤20% dari Laravel")
- Detil konteks ("100 concurrent users, ramp-up 30 detik, database 10K student records")
- Statistical validation (bukan hanya report mean/median, but p-value test)

Riset saya (WS-04 di atas) aim untuk address ketiga gap ini → RQ lebih mature & testable.
