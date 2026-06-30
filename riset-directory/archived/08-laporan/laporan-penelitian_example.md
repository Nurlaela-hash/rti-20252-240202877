# Laporan Penelitian

**Judul:** Performance and Security Evaluation of Mitigating JWKS Endpoint Flooding on Microservices Gateway Using Redis-PostgreSQL Hybrid Caching

**Peneliti:** Helmi Bahar Alim
**Target Publikasi:** Sinta 2 (Jurnal RESTI/Telematika) atau Scopus Q3–Q4
**Status Penelitian:** Tahap 1–4 selesai; Tahap 5 (draf naskah jurnal) sedang berjalan ([../07-manuskrip/](../07-manuskrip/))

---

## 1. Ringkasan Eksekutif

Penelitian ini merancang, mengimplementasikan, dan mengevaluasi secara empiris mekanisme **Redis-PostgreSQL Hybrid Caching** sebagai mitigasi kerentanan **JWKS Endpoint Flooding** pada API Gateway berbasis Go (Echo). Evaluasi dilakukan melalui eksperimen terkontrol: satu gateway dengan dua mode operasi (`CACHE_MODE=none` sebagai baseline dan `CACHE_MODE=hybrid` sebagai mitigasi), diuji terhadap 5 varian traffic (legitimate, dua varian serangan, dan dua varian campuran) masing-masing 40 replikasi — total **400 pengujian beban** menggunakan k6, dengan pengukuran latensi, throughput, metrik internal gateway (Prometheus), dan penggunaan resource container (CPU/memori).

**Temuan utama:**

- Mitigasi **tidak menambah overhead** pada kondisi normal (latensi hybrid sedikit lebih rendah dari baseline).
- Mitigasi **menurunkan beban query PostgreSQL sebesar 93,2%–99,997%** dan **CPU PostgreSQL dari 64–154% menjadi <2,5%** pada mayoritas skenario.
- Mitigasi **melindungi latensi traffic legitimate** saat sistem diserang ($D_{perf}$ p95 = -92,9% pada `mixed-unique`, -39,5% pada `mixed-pool`).
- Ditemukan **trade-off**: pada pola serangan dengan `kid` selalu baru (`*-unique`), rate-limiting berbasis UPSERT per `client_ip` di PostgreSQL menjadi titik kontensi *lock*, sehingga CPU PostgreSQL tetap tinggi (103–124%) dan latensi traffic penyerang pada mode hybrid justru lebih buruk daripada baseline.

Seluruh kode sumber, data eksperimen, skrip analisis, tabel, dan figure tersedia di repository ini (lihat §7 Lampiran untuk peta artefak).

---

## 2. Latar Belakang dan Rumusan Masalah

### 2.1 Latar Belakang

API Gateway pada arsitektur microservices umumnya memvalidasi JSON Web Token (JWT) dengan mengambil kunci publik penandatangan dari *JSON Web Key Set* (JWKS) berdasarkan *Key ID* (`kid`) pada header token. Pada implementasi naif, setiap `kid` yang belum dikenal memicu *lookup* baru ke backing store (database/Identity Service). Penyerang dapat mengeksploitasi pola ini — yang dalam penelitian ini disebut **JWKS Endpoint Flooding** (selaras dengan kelas kerentanan CVE-2026-48524, perlu diverifikasi — lihat [../02-literatur/matriks-literatur.md](../02-literatur/matriks-literatur.md)) — dengan membanjiri gateway menggunakan JWT ber-`kid` acak, sehingga beban *lookup* ke database bertumbuh linear terhadap *request rate* penyerang dan berpotensi menyebabkan *resource exhaustion* yang menurunkan kualitas layanan bagi pengguna sah.

### 2.2 Rumusan Masalah

1. Bagaimana merancang mekanisme caching pada API Gateway yang membatasi dampak JWKS Endpoint Flooding terhadap beban database backend, tanpa menambah latensi signifikan pada traffic legitimate?
2. Seberapa besar efektivitas skema Redis-PostgreSQL Hybrid Caching (positive & negative cache) dalam menurunkan beban query database dan penggunaan CPU selama serangan?
3. Bagaimana dampak ($D_{perf}$) mitigasi terhadap latensi traffic legitimate, baik pada kondisi normal maupun saat berjalan bersamaan dengan traffic serangan?
4. Apakah strategi serangan `kid` selalu baru (`unique`) vs `kid` berulang dari pool kecil (`pool`) menghasilkan efektivitas dan trade-off mitigasi yang berbeda?

### 2.3 Tujuan Penelitian

Detail tujuan & kontribusi: lihat [../01-proposal/proposal-penelitian.md](../01-proposal/proposal-penelitian.md) §3 dan §5, serta [../07-manuskrip/02-pendahuluan.md](../07-manuskrip/02-pendahuluan.md).

---

## 3. Metodologi dan Pelaksanaan

Penelitian dilaksanakan dalam 5 tahap. Bagian ini merangkum implementasi dan verifikasi setiap tahap; detail teknis lengkap ada pada dokumen `09-docs/tahap-N-*.md` yang dirujuk.

### 3.1 Tahap 1 — Perancangan Arsitektur & Skema Database

**Status: Selesai.** Dirancang arsitektur tiga komponen (Gateway Go/Echo, Redis sebagai L1 cache murni, PostgreSQL sebagai L2/*source of truth*), alur resolusi kunci (positive cache → negative cache → rate-limit PostgreSQL → query `signing_keys`), skema tabel `signing_keys` dan `rate_limit_counters` (dengan *stored procedure* `upsert_rate_limit_counter` untuk UPSERT atomik), dan skema key Redis (`jwks:kid:<kid>`, `jwks:negative:<kid>`). Mode eksperimen `CACHE_MODE=none|hybrid` dirancang sejak tahap ini agar perbandingan baseline-vs-mitigated dapat dilakukan pada infrastruktur identik.

... (truncated for brevity) ...
