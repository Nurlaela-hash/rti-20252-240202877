# WS-06: System-Experiment Mapping

> **Bab 6 — System Design sebagai Experimental Artifact**

---

## Ringkasan Materi

### Sistem = Instrumen Pengujian, Bukan Produk

Seorang engineer bertanya "apakah sistem bekerja?" — seorang peneliti bertanya "apa yang bisa dibuktikan sistem ini?" Sistem dalam riset adalah **artifact** — objek yang sengaja dibuat untuk menguji klaim spesifik.

### System as Experiment Model

```
RQ → Variable → System Component → Experimental Setup → Output
```

Setiap komponen sistem harus bisa ditelusuri ke variabel riset (top-down), dan setiap pengukuran harus menjawab RQ (bottom-up).

### Mapping Variabel ke Komponen

| Tipe Variabel | Peran di Sistem | Contoh |
|---------------|----------------|--------|
| **IV** (Independent) | Modul yang bisa di-toggle/swap | Algoritma A vs B |
| **DV** (Dependent) | Modul pengukuran | Logger, metrics collector |
| **CV** (Control) | Config yang dikunci | Dataset, parameter tetap |

Jika variabel tidak bisa di-map ke komponen apapun → arsitektur perlu didesain ulang.

### 4 Prinsip Desain Eksperimental

| Prinsip | Pertanyaan Kunci |
|---------|-----------------|
| **Traceability** | Komponen ini melayani variabel yang mana? |
| **Modularity** | Bisakah IV diubah tanpa memengaruhi yang lain? |
| **Controllability** | Apakah CV dieksternalisasi ke config file? |
| **Measurability** | Apakah sistem otomatis menghasilkan data yang dibutuhkan? |

### Variable Isolation melalui Arsitektur

- **Modular architecture** — Pisahkan berdasarkan variabel
- **Configuration-driven** — Ubah config (YAML/JSON), bukan code
- **Feature toggles** — On/off flag untuk ablation study

  Contoh config YAML dengan feature toggles:
  ```yaml
  model:
    type: cnn          # IV: ganti "rf" untuk kondisi baseline
  features:
    use_temporal: true  # toggle komponen temporal
    use_normalization: true  # toggle preprocessing
  experiment:
    seed: 42
    runs: 5
  ```
  Dengan pendekatan ini, berbeda kondisi eksperimen = berbeda satu baris config, **tanpa mengubah kode**.

### Research vs Engineering

| Aspek | Engineering | Research |
|-------|------------|----------|
| Tujuan sistem | Memenuhi kebutuhan user | Menguji hipotesis, menghasilkan bukti |
| Arsitektur | Optimasi performa & skalabilitas | Optimasi isolasi variabel & reprodusibilitas |
| Konfigurasi | Sering hardcoded | Dieksternalisasi ke config file |
| Fitur tambahan | Menambah nilai user | Menambah noise jika tidak terkait RQ |

### Istilah Penting

- **Artifact** — Objek yang sengaja dibuat untuk memecahkan masalah atau menguji proposisi
- **Traceability** — Kemampuan menelusuri hubungan RQ → variabel → komponen → output
- **Variable Isolation** — Mengubah hanya satu variabel sambil menahan yang lain konstan
- **Ablation Study** — Menguji kontribusi tiap komponen dengan melepasnya satu per satu
- **Configuration-driven Execution** — Semua parameter di config file, bukan hardcoded

---

## Template A.6 — Mapping RQ ke Arsitektur Sistem

```
SYSTEM-EXPERIMENT MAPPING

Research Question: ____________________

Variable → Component Mapping:
| Variabel | Tipe | Komponen Sistem | Cara Manipulasi/Pengukuran |
|----------|------|-----------------|---------------------------|
|          | IV   |                 |                           |
|          | DV   |                 |                           |
|          | CV   |                 |                           |

4 Prinsip Desain:
  [ ] Traceability — Setiap komponen bisa ditelusuri ke variabel
  [ ] Variable Isolation — IV bisa diubah tanpa mengubah CV
  [ ] Measurement Integration — Pengukuran DV built-in
  [ ] Reproducibility — Setup bisa direkonstruksi

Experimental Setup:
  Input data     : ____________________
  Parameter      : ____________________
  Output format  : ____________________
```

---

## Latihan 1 — Variable-to-Component Mapping

Gunakan RQ dan variabel dari WS-05. Petakan ke komponen sistem.

**RQ:** Apakah Express.js menghasilkan Response Time, Throughput, dan Resource Usage yang signifikan berbeda dari Laravel pada REST API CRUD kompleks dengan 100K rows database dan 100 concurrent users?

| Variabel | Tipe | Komponen Sistem | Cara Manipulasi / Pengukuran |
|----------|------|-----------------|---------------------------|
| **Framework** (Laravel vs Express.js) | **IV** | **Framework Abstraction Layer** — interface identik untuk CRUD, di-route ke implementation Laravel atau Express.js via config | Ganti `config.yml: framework: "laravel"` ↔ `"express.js"` → entrypoint beda, API endpoint identical |
| **Response Time (RT median & p95)** | **DV** | **Metrics Collector Module** — k6 performance testing tool capture RT per request; aggregate statistik | k6 probe `name: "response_time"` → output JSON dengan percentiles; automated parsing |
| **Throughput (RPS)** | **DV** | **Metrics Collector Module** — k6 track successful requests per second steady-state | k6 `name: "http_reqs"` counter + duration timer → calculate RPS |
| **Error Rate** | **DV** | **Metrics & Monitoring** — capture HTTP 4xx/5xx responses, queries that fail | k6 `http_req_failed` metric + database query error logs |
| **CPU/Memory Usage** | **DV** | **System Monitor Module** — Linux top/vmstat capture process resource; triggered at test start/end | Script `monitor_resources.sh` run in background, log every 100ms to CSV |
| **Database Size (100K rows)** | **CV** | **Database Seeding Module** — MySQL dataset initialization | SQL script auto-populate 100K rows identik di Laravel & Express.js MySQL; locked via schema versioning |
| **Load Profile (100 VU)** | **CV** | **k6 Load Test Script** — hardcoded ramp-up profile | VU = 100, rampUp = 30s, duration = 120s; same for both framework tests |
| **Query Complexity** | **CV** | **API Endpoint Specification** — multi-join, filter, pagination logic | GET `/api/products?category=X&brand=Y&price_min=A&price_max=B&page=1&limit=20` → identik parse & join 5 tables di both framework |

**Apakah semua variabel bisa di-map?** [✓] Ya / [ ] Tidak
> Semua variabel (1 IV + 6 DV + 3 CV) bisa di-map ke komponen sistem konkrit. IV (framework) di-isolasi via modular layer. DV semua punya measurement integration (k6 probe, system monitor). CV di-config external (YAML), bukan hardcoded.

---

## Latihan 2 — 4 Prinsip Desain

Evaluasi desain sistem terhadap 4 prinsip.

| Prinsip | Status | Bukti / Penjelasan |
|---------|--------|-------------------|
| **Traceability** | ✅ **Penuh** | Setiap komponen system punya label RQ → IV/DV/CV → Component (di Latihan 1). Contoh: Response Time DV → Metrics Collector → k6 probe output → analyze script. Chain tergambar clear. |
| **Modularity** | ✅ **Tinggi** | Framework abstraction layer memisahkan Laravel & Express.js implementation. Ganti 1 line config → berbeda framework behavior, no code change. Database module terpisah (reusable untuk both). Load test module (k6) framework-agnostic. |
| **Controllability** | ✅ **Penuh** | CV di-eksternalisasi ke `experiment_config.yaml`: `database: {size: 100000}`, `load: {concurrent_users: 100, rampup_sec: 30}`, `query_complexity: {fields_filter: 5, table_joins: 5}`. Mengubah CV = edit YAML saja, no recompilation. |
| **Measurability** | ✅ **Otomatis** | k6 automatically collect RT/Throughput/Error; monitoring script (Linux top) capture CPU/Memory; semua output ke structured format (JSON, CSV) → automated parsing untuk statistical analysis. Zero manual measurement. |

**Prinsip mana yang paling sulit dipenuhi?** 
> **Modularity** untuk framework-specific ORM behavior. Laravel Eloquent dan Express.js Sequelize punya subtle difference dalam query optimization, caching, connection pooling. To maintain fair comparison, perlu ensure both framework use identical ORM features (no Laravel-specific query optimization) → requires careful code review, bukan pure config swap.

**Strategi untuk mengatasinya:**
> (1) Design REST API endpoint bukan berdasarkan framework convention, tapi specification (OpenAPI/Swagger). Baik Laravel maupun Express.js harus implement endpoint identik.
> (2) Benchmark ORM directly: test Eloquent vs Sequelize separate untuk understand baseline difference → if significant, standardize ke raw SQL untuk both → if perbedaan persist, acknowledge di analysis ("framework optimization difference")
> (3) Code review checklist: database connection pooling size identical, caching disabled, transaction level same, character set identical. → Test on identical schema & query.

---

## Latihan 3 — Ablation Study Planning

Jika sistem memiliki 3 komponen utama, rencanakan ablation study.

> **Catatan**: Untuk research ini (Framework comparison), ablation adalah investigasi "apa drives perbedaan performa?" Kondisi:
> - **Full** = production-ready: caching enabled, connection pool, query optimization, error handling
> - **Reduced complexity** = minimal features untuk isolate runtime difference
> - **Raw SQL** = bypass ORM untuk eliminate ORM overhead

| Kondisi | Framework Config | DB Query Complexity | Framework Optimization | Tujuan / Hasil Harapkan |
|---------|-----------|-----------|----------|-------------|
| **Full (Baseline)** | Native driver (Eloquent for Laravel, Sequelize for Express.js) | Multi-join 5 tables, filter 5+ fields, pagination | ✅ Caching, connection pooling, query optimization enabled | **Real-world comparison**: actual framework capability |
| **Reduced – No ORM** | Raw SQL via driver | Same multi-join 5 tables, filter 5+ fields, pagination | ✅ Caching, connection pooling enabled | **Query overhead isolation**: eliminate ORM layer → if RT difference disappear, ORM is bottleneck |
| **Reduced – No Cache** | Native driver (ORM) | Multi-join 5 tables, filter 5+ fields, pagination | ❌ Caching disabled, ❌ connection pooling disabled | **Runtime difference isolation**: eliminate middleware overhead → if RT same, difference is caching behavior, not core runtime |
| **Simple – CRUD Minimal** | Native driver (ORM) | Simple SELECT 1 table, no filter, no join, no pagination | ❌ All optimization disabled | **Baseline runtime**: absolute minimum overhead → both framework should be ~equal (if not, runtime engine difference) |

**Komponen mana yang diprediksi paling berkontribusi?** 
> **Connection Pooling** (~30% estimated contribution untuk Express.js advantage). Node.js event-loop bisa reuse connection pool efficiently; PHP shared-nothing architecture create new process/connection per request.

**Mengapa?**
> (1) Laravel (PHP-FPM): setiap HTTP request spawn PHP process → database connection baru → handshake overhead (authentication, setup)
> (2) Express.js (Node.js event-loop): single process, persistent connection pool → reuse connection → lower latency  
> (3) Ablation condition "Reduced – No Cache" akan expose ORM caching difference — if Laravel query cache lebih baik, might eliminate RT advantage
> (4) Ablation "Reduced – No ORM" (raw SQL) will show if ORM overhead significant — if raw SQL RT sama di both framework, then Sequelize ≈ Eloquent cost is equal, diff is elsewhere
> (5) Simple CRUD baseline akan show if runtime engine (PHP vs Node) punya fundamental difference — prediction: Express.js still faster (event-loop vs synchronous blocking) even pada minimal workload

---

## Refleksi

> Apa risiko jika sistem dibangun seperti produk (monolitik, fitur lengkap) lalu baru dilakukan eksperimen? Mengapa arsitektur modular penting untuk riset?

**Jawaban:**

**Risiko membangun sistem seperti produk dulu, eksperimen belakangan:**

1. **Variable Confounding** (uncontrolled variables)
   - Contoh: Produk Laravel punya built-in middleware (CORS, CSRF, rate limit), caching layer, load balancer
   - Eksperimen jadi tidak fair: apakah Express.js lebih cepat sama Laravel-core, atau lebih cepat dibandingkan Laravel + middleware-stack?
   - No way to isolate which factor caused perbedaan (IV tidak terisolasi)

2. **Reproducibility Crisis**
   - Produk code paling ~5000 baris kompleks, hardcoded config, implicit dependencies
   - Untuk replikasi eksperimen, orang lain harus: reverse-engineer config, setup identical environment, understand implicit assumptions
   - Hasil riset = not reproducible, karena system tidak transparent

3. **Measurement Blind Spot**
   - Dibangun untuk "production ready" (memiliki caching, error handling, logging) → cost overhead besar
   - Ketika ngukur RT, tidak bisa tahu: 30ms itu karena framework logic, atau karena logging overhead?
   - Jika measurement tidak integrated dari awal, data akan "noisy" dan conclusion tidak valid

4. **Custom Reverse-Engineering Eksperimen**
   - Ketika sadar "oh saya butuh isolate caching layer" → rework code, remove caching specifically
   - Hasil: multiple version code (production + experiment-version1 + experiment-version2) → maintenance nightmare
   - "Extraneous variables" mulai multiply

**Mengapa arsitektur modular penting untuk riset:**

1. **IV Isolation** — Framework bisa di-swap tanpa data/network/logic-layer berubah
   - Modular = each layer (DB layer, API logic, metrics collection) independent
   - If Laravel DB layer ≠ Express.js DB layer → di-mock dengan abstraction layer
   - Result: IV (framework) truly isolated, DV measured clean

2. **Configuration-Driven Hypothesis Testing** — hypothesis dapat ditest dengan config change, bukan code rewrite
   - Hypothesis 1: "If Express.js faster, itu karena async I/O" → test: disable Express.js async, run sync version → RT same as Laravel?
   - If config-driven: change 1 YAML line → run test. If code-heavy monolith: need rewrite, compile, debug — expensive & error-prone

3. **Reproducibility at Scale** — other researchers bisa repro dalam hours, bukan weeks
   - System architecture documented (each module's role) + config explicit (YAML files) + setup automated
   - Someone di universitas lain: download code + config →  python run_experiment.py → get data
   - Versus: "siapa bisa repro? monolitik, implicit assumptions, setup tribal knowledge"

4. **Measurement Precision** — each metric bisa isolated & validated independently
   - Modular monitoring: k6 probe untuk RT (no overhead), OS monitor untuk CPU (separate tool), database log untuk query count
   - If all tangled dalam 1 system → overhead accumulates, noise increases, effect size shrinks, statistical power down

**Analogi:**
- **Produk-first approach** = "Bangun mobil lengkap (mesin, transmisi, dashboard, hiburan sistem) → lalu try test 'apakah mesin A faster dari mesin B'" → tapi dashboard-nya differ, hiburan overhead beda → hasilnya confounded, meaningless
- **Modular-first approach** = "Design framework: mesin itu modular (plug-in different engines) → dashboard light-weight (no overhead) → test engine A vs B di same chassis" → hasil clean, interpretable

**Best practice untuk research system design:**
1. Identify IV/DV/CV from RQ → map to architecture → design interface abstraction
2. Each component minimal, focused (Single Responsibility Principle) → easy to replace/measure
3. Externalize all tunable parameter to config files (YAML/JSON)
4. Measurement integrated from day 1 (not retrofitted) → automated data collection, no manual logging
5. Reproduce-ability checklist: can colleague run experiment on different hardware via just changing config? if not, architecture not isolated enough.
