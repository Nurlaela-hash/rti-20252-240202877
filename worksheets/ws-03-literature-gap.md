# WS-03: Literature Mapping & Gap

> **Bab 3 — Literature Review, Research Gap & Baseline**

---

## Ringkasan Materi

### Literature Review = Positioning, Bukan Ringkasan

Literature review bukan merangkum paper satu per satu. Pendekatan yang benar adalah **concept-centric** — organisasi berdasarkan tema, metode, atau variabel. Tujuan: menemukan **pola, kontradiksi, dan gap**.

**Perbandingan pendekatan Author-centric vs Concept-centric:**

| Aspek | Author-centric (Hindari) | Concept-centric (Gunakan) |
|-------|--------------------------|---------------------------|
| Struktur | Per penulis/paper ("Rahman et al. menyatakan...") | Per konsep/metode ("Pendekatan berbasis transformer") |
| Tujuan | Ringkasan isi paper | Perbandingan metode & identifikasi gap |
| Contoh paragraph | "Rahman (2023) pakai CNN. Lee (2022) pakai LSTM. Zhang (2021) pakai RF." | "Tiga pendekatan dominan: CNN digunakan oleh 4 paper untuk representasi fitur visual; LSTM untuk data sekuensial; RF sebagai baseline klasik." |
| Hasil akhir | Daftar paper | Peta pengetahuan + gap yang teridentifikasi |

### Empat Jenis Research Gap

| Jenis Gap | Deskripsi | Contoh |
|-----------|----------|--------|
| **Performance Gap** | Performa belum memadai | Akurasi deteksi hanya 78% pada kasus tertentu |
| **Method Gap** | Pendekatan belum diterapkan | Belum ada yang pakai transformer untuk task ini |
| **Data Gap** | Dataset terbatas/tidak representatif | Semua studi pakai dataset sintetis |
| **Context Gap** | Belum diuji pada konteks berbeda | Belum ada evaluasi di negara berkembang |

Gap terkuat = kombinasi 2+ jenis.

### Systematic Search Strategy

1. **Database utama**: IEEE Xplore, ACM DL, Scopus
   - Akses IEEE/ACM melalui jaringan kampus atau VPN institusi
   - Alternatif bebas biaya: Google Scholar, ResearchGate ([researchgate.net](https://www.researchgate.net)), arXiv ([arxiv.org](https://arxiv.org))
2. **Boolean query** yang terdokumentasi eksplisit
   - Contoh: `("anomaly detection" OR "intrusion detection") AND ("deep learning" OR "neural network") NOT ("medical imaging")`
   - Gunakan tanda kutip untuk frasa eksak; AND/OR/NOT mengontrol scope
3. **Snowballing** — dua arah:
   - **Backward snowballing**: buka daftar referensi di paper kunci → telusuri paper yang dikutip
   - **Forward snowballing**: di Google Scholar, klik "Cited by" di bawah paper kunci → temukan paper yang mengutipnya
   - Ulangi 1–2 tingkat untuk membangun cakupan komprehensif
4. Klaim "belum ada penelitian" harus didukung **bukti pencarian**

### Baseline Selection — 3 Kriteria

| Kriteria | Pertanyaan |
|----------|-----------|
| **Relevan** | Apakah menyelesaikan masalah yang sama? |
| **Representatif** | Apakah mewakili common practice? |
| **State-of-the-Art** | Apakah terbaru/terbaik? |

Membandingkan deep learning 2024 dengan decision tree sederhana tanpa justifikasi = **straw man comparison** (perbandingan tidak jujur).

### Research vs Engineering

| Aspek | Engineering | Research |
|-------|------------|----------|
| Tujuan baca literatur | Mencari solusi yang sudah ada | Memahami apa yang belum terjawab |
| Cara membaca paper | Tutorial, how-to | Metode, limitasi, gap |
| Baseline | Framework terpopuler | State-of-the-art yang rigorous |
| Dokumentasi pencarian | Tidak diperlukan | Wajib (reproducible) |

### Istilah Penting

- **Concept-centric** — Organisasi literatur berdasarkan konsep/metode, bukan per penulis
- **Snowballing** — Backward (telusuri referensi) + Forward (cari yang mengutip paper kunci)
- **Research Position** — Pernyataan eksplisit posisi riset terhadap studi sebelumnya
- **Straw man comparison** — Memilih baseline lemah agar metode sendiri terlihat lebih baik

---

## Template A.3 — Literature Mapping & Gap Identification

```
LITERATURE MAPPING

Topik      : ____________________
Database   : ____________________
Query      : ____________________
Tahun      : ____________________
Hasil awal : ____ paper → Screening → ____ paper final

Literature Matrix (concept-centric):

| Study | Tahun | Method | Data | Result | Limitation |
|-------|-------|--------|------|--------|------------|
|       |       |        |      |        |            |

Pola yang ditemukan:
  Metode dominan     : ____________________
  Dataset umum       : ____________________
  Limitasi berulang  : ____________________

GAP IDENTIFICATION

Gap 1: [Jenis: performance / method / data / context]
  Deskripsi    : ____________________
  Bukti        : ____________________
  Signifikansi : ____________________

Gap 2: [Jenis: ____]
  Deskripsi    : ____________________
  Bukti        : ____________________
  Signifikansi : ____________________

Baseline Selection:
| Baseline | Relevansi | Representatif | Source |
|----------|-----------|---------------|--------|
|          |           |               |        |
```

---

## Latihan 1 — Concept-Centric Literature Table

Gunakan topik riset dari WS-02. Cari minimal 5 paper relevan menggunakan database akademik.

> **Panduan pencarian:**
> - Database: IEEE Xplore, ACM DL, Google Scholar, atau ResearchGate
> - Tulis query Boolean yang digunakan: contoh `("object detection" OR "image classification") AND ("edge computing") NOT ("medical")`. Dokumentasikan query secara eksplisit.
> - Akses gratis: buka Google Scholar → cari judul paper → klik [PDF] jika tersedia, atau akses lewat campus VPN

**Topik riset:** Perbandingan Performa Laravel (PHP) dan Express.js (Node.js) dalam Pengembangan REST API
**Query pencarian:** `("Laravel" OR "PHP") AND ("Express.js" OR "Node.js") AND ("REST API" OR "performance" OR "comparison")`
**Database:** Google Scholar, Scopus (akses melalui kampus), arXiv

### Literature Matrix (Concept-Centric)

Organisasi berdasarkan 3 konsep utama: **(1) Performance Testing Methods**, **(2) Key Performance Metrics**, **(3) Framework Characteristics**

| # | Study | Tahun | Testing Method | Metrics Utama | Result Utama | Framework | Limitasi |
|---|-------|-------|--------|---------|--------|------------|----------|
| 1 | Rompis & Aji | 2018 | JMeter (10K concurrent requests) | Response Time, CPU Usage, RAM | Node.js tercepat (~50ms), PHP balanced | Node.js, PHP, Python | Hanya HTTP GET/POST, tidak CRUD lengkap |
| 2 | Siahaan & Wijaya | 2024 | Apache JMeter (50 virtual users) | Response Time, Throughput, Error Rate | Laravel: 1745.7ms, Express.js lebih cepat raw | Laravel, Express.js | Satu skenario (student data), belum multi-operasi |
| 3 | Pratama & Farisi | 2024 | k6.io (Load, Spike, Stress Testing) | Successful Requests, CPU, Memory, Response Time | Go > Node.js > PHP (raw SQL lebih cepat dari ORM) | Node.js, PHP, Go | 50 rows data dummy, scalability belum jelas |
| 4 | Mosul et al. (JITET) | 2024 | k6 (50 virtual users) | Response Time, Throughput, CPU, RAM, Dev Time | Node.js: 3x throughput, 11% CPU; Laravel: more stable auth | Laravel, Node.js | To-Do List sederhana, tidak enterprise use-case |
| 5 | Azzahidi et al. (JUTIF) | 2025 | k6.io (Load Testing) | Response Time, Throughput, Resource Usage | Spring Boot best overall; Laravel + FrankenPHP emerging | Spring Boot, Flask, Express.js, Laravel, Gin | 5 framework berbeda, komparasi fairness sulit |

**Pola yang terlihat — Metode dominan:**
- **Performance Testing Tools**: JMeter (2018), Apache JMeter (2024), k6.io (2024-2025) — tren dari JMeter ke k6 karena fleksibilitas JavaScript
- **Kriteria Evaluasi**: Response Time, Throughput (RPS), CPU/RAM Usage, Error Rate — **konsisten di semua paper**
- **Skenario Pengujian**: Load testing dominan; beberapa (Pratama) juga gunakan Spike & Stress testing
- **Query Methods** (pada paper Pratama): Raw SQL > ORM > Query Builder — menunjukkan impact signifikan

**Limitasi yang berulang:**
1. **Sample Size Data**: Semua menggunakan data dummy kecil (40-50 rows) — **konteks real-world database besar belum jelas**
2. **Skenario Terbatas**: Mayoritas CRUD sederhana tanpa operasi kompleks (join, aggregation, transaction)
3. **Environment/Hardware**: Spesifikasi server berbeda di setiap studi — **baseline hardware tidak konsisten**
4. **Development Time**: Hanya 1 paper (Mosul) measure dev time — **aspek developer productivity kurang dieksplorasi**
5. **Komparasi Framework**: Mayoritas 2-3 framework; sulit identifikasi framework mana "terbaik" secara universal

---

## Latihan 2 — Gap Identification

Berdasarkan tabel di Latihan 1, identifikasi gap.

| Jenis Gap | Ditemukan? | Gap Statement |
|-----------|-----------|---------------|
| Performance Gap | ✓ Ya | Pada large-scale production environment (1000+ users), performa baseline Laravel vs Express.js belum jelas; mayoritas studi hanya 50 virtual users |
| Method Gap | ✓ Ya | Belum ada perbandingan systematic pada **transactional complexity** (ACID properties, multi-step operations) dan **API maturity** (versioning, caching, pagination) |
| Data Gap | ✓ Ya | Semua studi menggunakan data dummy minimal (50 rows); belum ada evaluasi dengan realistic database size (100K+ rows, complex schema) |
| Context Gap | ✓ Ya | Evaluasi terbatas pada "greenfield" REST API; belum ada studi pada **legacy system integration** atau **real-world API requirements** (authentication, rate limiting, async operations) |

**Gap utama utama yang dipilih:** 
- **Primary Gap (Performance + Data + Context)**: Performa Laravel vs Express.js pada REST API dengan transactional complexity tinggi, realistic database scale, dan production-ready requirements (authentication, caching, async jobs) masih belum diteliti secara komprehensif.
- **Secondary Gap (Method)**: Belum ada framework **feature parity** dalam evaluasi — apakah perbedaan performa murni dari runtime/engine atau dari library/middleware yang digunakan?

**Mengapa gap ini penting (bukan sekadar "belum ada yang meneliti")?**
> Keputusan pemilihan framework di Indonesia sering didasarkan hanya pada "Laravel familiar" atau "Node.js trendy," bukan bukti empiris. Dengan data dummy simple & environment terkontrol, studi sebelumnya memberikan **misleading guidance**. Studi ini akan menjawab: "Untuk aplikasi **real Indonesia** (e-commerce, manajemen SDM, sistem akademik) dengan database besar & complex business logic, framework mana yang truly superior dalam **developer productivity + system reliability**?"

---

## Latihan 3 — Baseline Selection

Pilih 2 baseline dari literatur yang sudah dibaca.

| # | Baseline | Testing Method | Mengapa Relevan | Mengapa Representatif | SOTA? | Sumber Year |
|---|----------|--------|-------------|----------------------|-------|----------|
| 1 | **Express.js + k6 Load Testing (50 VU)** | k6.io dengan load testing scenarios | Task identik: REST API performance evaluation; Express.js adalah state-of-the-art untuk Node.js ecosystem | Digunakan di 4 dari 5 paper terbaru (2024-2025); common practice di industri | Ya — Express.js konsisten performant | Siahaan & Wijaya, 2024; Mosul et al., JITET |
| 2 | **Laravel + Apache/k6 Testing (dev time tracked)** | Apache JMeter / k6.io dengan response time + dev time | Perbandingan fair harus include developer experience, bukan hanya raw performance; Laravel adalah framework PHP paling mature & widely-adopted | Laravel: 10.12% di Stack Overflow 2021, PHP 82% web server (w3techs); represent "enterprise PHP reality" | Ya — Laravel latest (v11+) dengan best practices | Siahaan & Wijaya, 2024; Mosul et al., JITET |

**Apakah pemilihan baseline ini bisa dianggap straw man?** [ ] Ya / [✓] Tidak
> Justifikasi: Kedua baseline dipilih berdasarkan 3 kriteria SOTA: (1) **Relevansi**: kedua framework solve the same problem (REST API); (2) **Representatif**: Express.js & Laravel adalah top choices di industri (survey Stack Overflow, w3techs); (3) **Recency**: baseline dari 2024-2025, bukan versi lama. Testing methodology (k6 modern, include dev time di Mosul study) menunjukkan fairness — tidak hanya raw speed, tapi juga developer experience & maintainability.

---

## Refleksi

> Apa perbedaan antara "belum ada yang meneliti ini" (klaim tanpa bukti) dengan research gap yang valid? Bagaimana cara membuktikan bahwa sebuah gap benar-benar ada?

**Jawaban:**

**Perbedaan fundamental:**
1. **"Belum ada yang meneliti" (Klaim Tanpa Bukti)**: Hanya pernyataan, tanpa dokumentasi pencarian sistematis. Peneliti tidak menunjukkan boolean query, database mana yang dicek, atau paper apa saja yang dievaluasi. Hasilnya: claim "unique contribution" yang sebenarnya fiktif.

2. **Research Gap Valid (Evidence-Based)**: 
   - Didukung **systematic search** (query Boolean documented, database spesifik)
   - Menganalisis literatur secara **concept-centric** (bukan hanya daftar paper)
   - Mengidentifikasi **pola konsisten** dalam limitasi (gap bukan anomali tapi trend)
   - Menunjukkan **mengapa gap itu penting** (impact ke industri/akademia, bukan sekadar "belum diteliti")

**Cara membuktikan gap benar-benar ada:**
1. **Document the search thoroughly**: Tuliskan query Boolean → jumlah hasil awal → screening criteria → jumlah final. Contoh:
   ```
   Query: ("Laravel" OR "PHP") AND ("performance" OR "benchmark") 
   Initial results: 450 papers
   Inclusion: published 2020+, REST API context, explicit performance metrics
   Final: 5 papers
   → Mengidentifikasi trends dalam 5 paper tersebut  
   → Menemukan pola (semua gunakan small dummy data)
   → Gap: Belum ada evaluasi dengan realistic dataset
   ```

2. **Analyze the pattern, not just the absence**:
   - Jangan cukup bilang "belum ada" → harus tunjukkan **bukti** apa yang gap-nya
   - Contoh good: "Semua 5 paper gunakan max 50 rows data; none evaluasi on 1M+ row production database" ← ini bukti konkret
   - Contoh bad: "Belum ada perbandingan Laravel vs Express.js" ← tapi Paper Siahaan (2024) sudah exist!

3. **Connect gap to research contribution**: 
   - Gap ≠ "belum ada" tapi "**ini research yang akan saya lakukan akan fill yang gap ini**"
   - Dalam konteks study ini: "5 papers exists, tapi gap-nya: (1) none at realistic scale, (2) none dengan transactional complexity, (3) none track dev productivity. **Studi ini akan address ketiga gap itu simultaneously.**"

**Kesimpulan**: Gap yang valid = **intersection antara literature abundance + identified limitation + clear research contribution**.
