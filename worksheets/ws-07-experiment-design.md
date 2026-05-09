# WS-07: Experimental Design & Validity

> **Bab 7 — Experimental Design & Validity**

---

## Ringkasan Materi

### Correlation ≠ Causality

Kausalitas membutuhkan 3 syarat:
1. **Covariance** — X dan Y bergerak bersama
2. **Temporal precedence** — X berubah sebelum Y
3. **Elimination of alternatives** — Tidak ada faktor lain yang menjelaskan Y

Controlled experiment adalah satu-satunya metode yang bisa membuktikan kausalitas.

### Empat Jenis Validitas

| Jenis | Pertanyaan | Ancaman Umum |
|-------|-----------|-------------|
| **Internal** | Apakah hubungan IV→DV nyata? | Confounding variable, selection bias |
| **External** | Apakah bisa digeneralisasi? | Dataset terlalu spesifik |
| **Construct** | Apakah mengukur konsep yang benar? | Metrik tidak sesuai |
| **Conclusion** | Apakah kesimpulan statistik valid? | Sample size kecil, uji salah |

Internal dan external validity sering berkonflik: semakin terkontrol (internal kuat) → semakin artificial (external lemah).

### Tiga Tipe Eksperimen dalam Riset TI

| Tipe | Deskripsi | Kapan Digunakan |
|------|----------|----------------|
| **Comparison Study** | Metode A vs B pada kondisi identik | Membandingkan pendekatan berbeda |
| **Ablation Study** | Full system → lepas komponen satu per satu | Mengukur kontribusi tiap komponen |
| **Parameter Study** | Variasikan satu parameter, amati dampak | Uji sensitifitas/robustness |

### Fairness dalam Perbandingan

Perbandingan yang adil = **kondisi identik** untuk semua metode: dataset sama, preprocessing sama, tuning effort sebanding, environment sama, metrik sama.

Contoh tidak adil: Transformer (30 fitur tambahan + Bayesian optimization) vs RF (default params) → hasilnya misleading.

### Threats to Validity = Diidentifikasi Sebelum Eksperimen

Ancaman validitas harus diidentifikasi **sebelum** eksperimen dan mitigasinya dirancang sebagai bagian dari desain — bukan ditulis sebagai boilerplate setelah selesai.

### Research vs Engineering

| Aspek | Engineering | Research |
|-------|------------|----------|
| Tujuan testing | Memastikan sistem memenuhi requirement | Membuktikan hubungan kausal antar variabel |
| Baseline | Versi sebelumnya (last release) | Metode tervalidasi dari literatur |
| Kegagalan | Bug → fix → release | H₀ tidak ditolak → tetap kontribusi ilmiah |
| Sukses | 100% test pass | Evidence valid — mendukung atau menolak hipotesis |

### Istilah Penting

- **Causality** — Hubungan sebab-akibat (covariance + temporal + elimination)
- **Controlled Experiment** — Ubah satu variabel, kontrol sisanya, amati efek
- **Fairness** — Semua metode diuji pada kondisi yang benar-benar identik
- **Threats to Validity** — Faktor yang bisa melemahkan kesimpulan jika tidak dimitigasi
- **Conclusion Validity** — Validitas statistik: power, sample size, uji yang tepat

---

## Template A.7 — Desain Eksperimen Lengkap

```
EXPERIMENT DESIGN

Research Question : ____________________
Hypothesis        : ____________________
Tipe Eksperimen   : [ ] Comparison  [ ] Ablation  [ ] Parameter

Kondisi Eksperimen:
| Kondisi | Deskripsi | IV Value | CV Settings |
|---------|-----------|----------|-------------|
| Control |           |          |             |
| Treatment |         |          |             |

Fairness Checklist:
  [ ] Dataset identik untuk semua kondisi
  [ ] Preprocessing setara
  [ ] Tuning effort setara
  [ ] Environment identik
  [ ] Metrik evaluasi sama

Threat Analysis:
| Threat Type | Ancaman Spesifik | Mitigasi |
|-------------|-----------------|----------|
| Internal    |                 |          |
| External    |                 |          |
| Construct   |                 |          |
| Conclusion  |                 |          |

Statistical Plan:
  Uji statistik   : ____________________
  Justifikasi      : ____________________
  Alpha            : ____________________
  Effect size min  : ____________________
```

---

## Latihan 1 — Desain Eksperimen

Susun desain eksperimen berdasarkan RQ, variabel, dan sistem dari WS-04 sampai WS-06.

**RQ:** Apakah Express.js menghasilkan Response Time, Throughput, dan Resource Usage yang signifikan berbeda dari Laravel pada REST API CRUD kompleks dengan 100K rows database dan 100 concurrent users?

**Tipe eksperimen:** [✓] Comparison / [ ] Ablation / [ ] Parameter

| Kondisi | Deskripsi | IV Value | CV Settings |
|---------|-----------|----------|-------------|
| **Baseline (Control)** | Laravel REST API implementation — production-ready setup dengan ORM (Eloquent), caching, connection pool, error handling | Framework: Laravel 11, PHP 8.2, Eloquent ORM | Database: MySQL 5.7, 100K rows, 5 tables joined; Load: 100 VU, ramp-up 30s, sustain 120s; Query complexity: 5-table join, 3+ field filters, pagination |
| **Treatment** | Express.js REST API implementation — functionally identical to Laravel but using Node.js runtime + Sequelize ORM + identical config | Framework: Express.js 4.18, Node.js 18.x, Sequelize ORM | **Identik dengan baseline**: Database MySQL 5.7, 100K rows, same schema; Load profile identical (100 VU, same ramp-up); Query complexity identical (same API endpoint logic) |
| **Ablation-1** | Express.js + Laravel both dengan Raw SQL (bypass ORM) — isolate ORM overhead | Raw SQL via native driver (identical for both) | Database, load, schema identik; tapi query dijalankan via raw SQL, bukan ORM |
| **Ablation-2** | Express.js + Laravel both dengan minimal caching, no connection pool — isolate connection overhead | Disable caching, connection pool size = 1 | Database, load, schema identik; per-request connection (PHp-FPM single process, Node disable pool) |

---

## Latihan 2 — Fairness Checklist

Evaluasi apakah desain eksperimen di Latihan 1 sudah fair.

| Kriteria | Status | Detail |
|----------|--------|--------|
| **Dataset identik** | ✅ **Penuh** | Baseline vs Treatment: sama-sama MySQL 5.7, 100K rows, identik schema (5 tables: products, categories, brands, reviews, inventory — all foreign keys defined identik), identik data distribution (uniform random category/brand, price range 10K-100M, stock 0-1000) |
| **Preprocessing setara** | ✅ **Penuh** | No preprocessing — both treat raw queries identik. Database index: same index on category_id, brand_id, price untuk both | 
| **Tuning effort setara** | ✅ **Tinggi** | Both framework disallow hand-tuning ("production defaults"). ORM config standard (cache disabled, connection pool 10, query timeout 30s identik). Prevent favoritism: "no Sequelize-specific query optimization, no Eloquent lazy-load tricks" |
| **Environment identik** | ✅ **Penuh** | Hardware: same server (CPU Intel i5, 4GB RAM, SSD); OS: Ubuntu 22.04 LTS identik; Network: localhost (no network jitter), MySQL & webserver same machine. Prevent: "Express.js runs on server A, Laravel on server B" |
| **Metrik evaluasi sama** | ✅ **Penuh** | Both measured by k6 (identical probe logic), Linux top (identik sampling interval 100ms), MySQL slow query log (identik threshold 100ms). No "Laravel measured by StressTester, Express measured by Artillery" |

**Ada yang tidak fair?** [ ] Ya / [✓] Tidak
> Semua kriteria sudah terpenuhi. Design tujuannya ensure: "Framework is the only variable" (IV), semua else controlled (CV).

---

## Latihan 3 — Threat Analysis

Identifikasi ancaman validitas untuk desain eksperimen ini.

| Threat Type | Ancaman Spesifik | Mitigasi |
|-------------|-----------------|----------|
| **Internal Validity** | Framework version creep — Laravel 11 updates change ORM behavior mid-test, Express.js npm packages auto-upgrade | Freeze versions: `package.json` lock exact semver, `composer.lock` committed. Use Docker—beide framework run same OS, same library versions reproducible. Test on 2 different dates: if results consistent, version not confounding |
| **Internal Validity** | Warmup effect — first n requests slower (JIT compile, memory alloc) | Run 2-minute warmup load BEFORE measurement starts. Only count steady-state requests (after warmup) for statistics |
| **Internal Validity** | Measurement overhead — k6 probe itself consuming CPU → slower response time measured | Run k6 on separate machine (load injector), not same server as app. Or isolate metric collection overhead: measure k6 latency independently, subtract from app latency |
| **External Validity** | 100K rows ≠ 1M rows — scaling non-linear (query optimizer changing strategy, memory pressure) | Design acknowledge scope: "findings apply to 100K-scale datasets." Suggest future: repeat load test on 1M, 10M rows. Document: "result not directly generalizable to larger catalogs without replication" |
| **External Validity** | Single query type — uniform 5-table join + 3 filter — not representative of diverse API load | Real e-commerce have: 90% simple GET (1-2 table), 5% medium join (3-4 table), 5% complex (5+ table). Ablation-3 (optional): synthetic load mix 90/5/5 ratio → if result change, query distribution is confounding |
| **Construct Validity** | Response Time definition ambiguous — does it include DB roundtrip, network roundtrip, request queue? | Define operationally: "Response Time = server processing time (measured k6 serverTime metric) = [server received request] to [server send response], exclude network latency." Validate via source code inspection and manual testing |
| **Construct Validity** | Throughput measured wrong — k6 RPS might count retries, or count partial successful requests | Define: "Throughput = (successful requests with HTTP 200) per second" — filter out 5xx errors, 4xx client error. Log request IDs → manual verification: spot-check 100 requests to ensure 200 status |
| **Conclusion Validity** | Small sample size — 100 VU × 2 min × ~50 RPS = 10,000 total requests, but many might be cache-hit (degenerate variance). Statistical power low | (1) Run eksperimen 3 times (independent runs) → compute mean & std per run; (2) Use non-parametric Mann-Whitney U test (robust to non-normal dist); (3) Pre-register effect size threshold: "declare significant difference if ≥15% RT improvement, p<0.05, AND difference consistent across 3 runs" |
| **Conclusion Validity** | Temporal autocorrelation — responses in load test not independent (if framework warming up, later requests faster) | Use t-test statistic that accounts for autocorr, OR divide test window into 3 sub-intervals: compare T1 vs T2 vs T3 → if RT consistent across intervals, data not autocorrelated |

**Ancaman mana yang paling sulit dimitigasi?** 
> **External Validity** — generalizability ke real-world production (1M+ rows, diverse query patterns, unpredictable traffic). Tidak bisa fully eliminate tanpa infinite runs.

**Mengapa?**
> Eksperimen by definition simplified (100K rows, uniform queries, steady 100 VU). Production = complex (1M rows, spike traffic 1000 VU, mixed query complexity). Mitigasi: Operasi transparent about limitation, design untuk "extensible" → future researchers easily change scale/load-mix without rewriting entire framework. Publish code & data open-source → enable reproduction di different context.

---

## Statistical Plan

| Elemen | Detail |
|--------|--------|
| **Uji statistik** | Mann-Whitney U Test (non-parametric, two-sided) — comparing Response Time median antara Laravel vs Express.js. Alasan: (1) RT distribution cenderung skewed (tail latency), bukan normal; (2) Non-parametric lebih robust |
| **Justifikasi uji** | ANOVA/t-test assume normal distribution. Preliminary test (Shapiro-Wilk on pilot data) likely reject normality (small p-value). Mann-Whitney U: ranked-based, tidak assume distribusi, cocok untuk latency data |
| **Alpha (significance level)** | α = 0.05 (two-tailed) — standard akademis. Konfidensial: reject H₀ jika p-value < 0.05 |
| **Effect size minimum** | **Practical significance**: RT perbedaan ≥15% dianggap "meaningful" (user noticeable). Cohen's d ≥ 0.5 (medium effect) dianggap "statistically meaningful." Pre-register: both threshold harus satisfied untuk declare "significant difference" |
| **Sample size & power** | Pilot data: assume RT Laravel μ=1000ms σ=200ms, Express.js μ=750ms σ=150ms (effect size d=1.25 "large"). n=100 per group (total 200 requests) → power ~95% untuk detect effect size d=1.2 pada α=0.05. Eksperimen actual: 100 VU × 2 min × 50 RPS ≈ 10K requests per framework >> 200, jadi power sangat tinggi |
| **Multiple comparison correction** | 6 primary metrics (RT, RPS, Error Rate, CPU, Memory, p95 latency). Bonferroni correction: α_adjusted = 0.05/6 ≈ 0.008. OR: pre-register **1 primary metric** (RT median) + others secondary → avoid multiple testing problem |
| **Planned analysis pipeline** | (1) Descriptive stats (mean, median, std per metric). (2) Visualize: boxplot + histogram RT. (3) Test normality (Shapiro-Wilk). (4) If normal: t-test; else Mann-Whitney U. (5) Compute effect size (Cohen's d). (6) Report 95% CI. (7) Sensitivity analysis: repeat test removing outliers, repeat on different load levels (sub-interval T1/T2/T3) |
| **Reporting standard** | Follow APA style: "Express.js (Mdn=X, IQR=Y) vs Laravel (Mdn=A, IQR=B); U=Z, p=0.04, d=0.8; 95% CI [lower, upper]." Transparensi: report effect size, confidence interval, NOT just p-value (avoid p-value misinterpretation) |

---

## Refleksi

> Sebuah paper melaporkan "metode kami mengalahkan semua baseline." Apa 3 pertanyaan pertama yang harus diajukan untuk mengevaluasi klaim ini?

**Jawaban:**

**1. "Apakah comparison truly fair — atau ada hidden advantage di metode yang diusulkan?"**
   - **Red flags to check:**
     - Apakah baseline ditune sebaik-baiknya, atau just default parameters?
       - Contoh unfair: "CNN dengan Bayesian hyperparameter optimization vs RF dengan default params" → CNN menang bukan karena algorithm, tapi karena tuning effort
     - Apakah baseline from literatur terbaru (2024) atau outdated (2015)?
       - Outdated baseline = strawman comparison (unfair)
     - Apakah environment identik? (same dataset, same hardware, same preprocessing)
       - Contoh unfair: "My CNN uses GPU acceleration, baseline RF uses CPU" → apakah CNN inherently superior atau hanya GPU-favored?
   - **Better question**: "Apakah perbandingan dilakukan pada kondisi yang truly identical untuk semua metode, termasuk hyperparameter tuning effort?"

**2. "Apakah 'mengalahkan baseline' diukur dengan metrik yang representative, atau hanya selected metrics yang favorable?"**
   - **Red flags to check:**
     - Berapa metric yang dilaporkan? Jika 10+ metrics, mungkin ada p-hacking (pilih yang signifikan, ignore yang tidak)
       - Contoh: report hanya accuracy (90% vs 88%), ignore precision/recall (mungkin F1-score  sama)
     - Apakah effect size significant secara praktis atau hanya statistik?
       - Contoh: "Method A = 90.001% vs Method B = 90.000%, p-value < 0.05" ← statistically significant tapi practically meaningless
     - Primary vs secondary metrics — apakah perbedaan mostly pada secondary?
   - **Better question**: "Apakah perbedaan 'beating baseline' adalah practically meaningful (effect size ≥ threshold yang signifikan), atau hanya statistical artifact?"

**3. "Apakah hasil reproducible — adakah bukti transpar tentang setup, code, & data?"**
   - **Red flags to check:**
     - Code open-source? 
       - Closed code = sulit verify, bisa ditulis ulang dengan "cherry-picking" hasil
     - Dataset available untuk public replication?
       - Proprietary data = sulit repro
     - Apakah paper provide all detail untuk rekonstruksi eksperimen (random seed, exact library version, hardware spec)?
       - Vague detail = reproducibility crisis
     - Apakah hasil "sensitive" terhadap hyperparameter changes?
       - Eksperimen yang robust: hasil konsisten across multiple runs/seeds. Fragile = sensitif terhadap kecil change
   - **Better question**: "Apakah result reproducible oleh orang lain (code available, data available, detail sufficient), dan robust terhadap reasonable variation dalam setup?"

**Intinya**: "Mengalahkan baseline" claims sering misleading karena:
- **Selection bias**: cherry-pick favorable baseline, favorable metric
- **Garden of forking paths**: try many analysis, report significant ones → p-hacking
- **Lack of transparency**: tak ada code/data/detail → impossible to verify

**Cara evaluasi responsible**:
1. Check fairness → apakah baseline equally tuned? Apakah environment controlled?
2. Check metric → effect size reported (tidak hanya p-value)? Both primary & secondary shown?
3. Check reproducibility → code available, data available, detail sufficient untuk repro?

Jika ketiga jawaban "tidak", klaim "mengalahkan baseline" adalah **suspicious — likely outcome dari unfair comparison, p-hacking, atau irreproducible setup**, bukan bukti metode truly superior.
