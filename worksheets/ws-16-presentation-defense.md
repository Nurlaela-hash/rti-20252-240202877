# WS-16: Presentation & Defense (UAS)

> **Bab 16 — Presentasi & Pertahanan Ilmiah**

---

## Ringkasan Materi

### Scientific Defense Model

```
Research Work → Presentation → Questioning → Defense → Evaluation → Acceptance
```

### Presentasi ≠ Ringkasan Paper

| Paper | Presentasi |
|-------|-----------|
| Dibaca (self-paced) | Didengar (presenter-paced) |
| Detail lengkap | Ide kunci + highlight |
| Tabel numerik detail | Grafik visual + angka kunci |
| Pembaca bisa re-read | Audiens dengar sekali |

**Prinsip:** Presentasi membutuhkan **reformulasi**, bukan kompresi. Medium berbeda = pendekatan berbeda.

### Claim-Evidence-Reasoning (CER)

Setiap jawaban defense harus memiliki:
1. **Claim** — Pernyataan yang dijawab
2. **Evidence** — Data/fakta pendukung
3. **Reasoning** — Logika yang menghubungkan evidence ke claim

**Contoh:**
| Pertanyaan | Bad Answer | Good Answer (CER) |
|-----------|-----------|-------------------|
| "Kenapa hanya 3 dataset?" | "Tiga sudah cukup" | "3 dataset mewakili variasi: small-clean, medium-clean, medium-noisy [E]. Generalisasi perlu validasi lanjut — listed as limitation [R]" |
| "Hasil DS-3 menurun?" | "Itu outlier" | "Ya, karena distribusi heavy-tail melanggar asumsi Gaussian [E]. Ini menunjukkan boundary condition metode [R]" |
| "Effect size?" | "p=0.003, jadi signifikan" | "Cohen's d=1.2 (large effect) [E] — bukan hanya signifikan tapi substansial [R]" |

### Slide Design — One Slide, One Message

**Optimal 9-Slide Plan (15 menit):**

| # | Slide | Waktu | Pesan |
|---|-------|-------|-------|
| 1 | Title + context | 1 min | Apa ini tentang apa |
| 2 | Problem + motivation | 2 min | Mengapa penting |
| 3 | Gap + RQ | 1.5 min | Apa yang belum terjawab |
| 4 | Method overview | 2 min | Bagaimana dijawab (diagram) |
| 5 | Key result — tabel | 2 min | Temuan utama |
| 6 | Key result — grafik | 2 min | Pola visual |
| 7 | Interpretation + failure | 2 min | Apa artinya |
| 8 | Limitation + future | 1.5 min | Batasan & arah |
| 9 | Conclusion + contribution | 1 min | Closing message |

### Anticipatory Defense

Prediksi pertanyaan berdasarkan kategori:

| Kategori | Contoh Pertanyaan |
|---------|------------------|
| Problem | "Mengapa masalah ini penting?" |
| Gap | "Bagaimana dengan studi X yang sudah menjawab ini?" |
| Method | "Mengapa metode ini, bukan Y?" |
| Results | "Bagaimana menjelaskan anomali di DS-3?" |
| Generalization | "Apakah bisa diterapkan di domain lain?" |

### Tiga Prinsip Jawaban

1. **Direct** — Jawab dulu, elaborasi kemudian
2. **Data-based** — Tunjuk evidence spesifik
3. **Honest** — Akui limitasi jika memang ada

### Jebakan Kognitif

1. "Presentasi = semua yang ada di paper" → terlalu padat
2. "Slide cantik = presentasi bagus" → konten > estetika
3. "Tidak bisa jawab = gagal" → "I don't know, but..." menunjukkan kejujuran
4. "Tidak perlu latihan — saya paham riset saya" → latihan = menemukan celah

---

## Template A.16 — Defense Preparation Sheet

```
DEFENSE PREPARATION

Slide Deck Plan:
  Total slides   : 11 (9 konten + 1 judul + 1 penutup)
  Time per slide : ~1.5 min
  Total time     : 15 menit

Slide Outline:
| # | Pesan Utama | Visual | Waktu |
|---|-------------|--------|-------|
| 1 | Title Slide — Perbandingan Performa Laravel 11 vs Express.js | Slide Judul, Identitas Peneliti | 1 min |
| 2 | Problem — Bias pemilihan framework & ketidakrealistisan benchmark CRUD sederhana | Diagram motivasi & isu hosting cloud | 2 min |
| 3 | Gap + RQ — Ketiadaan pengujian terisolasi & signifikansi statistik | Tabel gap positioning dengan studi terdahulu | 1.5 min |
| 4 | Method Overview — Arsitektur Docker Compose (1 CPU, 512MB RAM) & beban CRUD k6 | Diagram topologi sistem & query composition | 2 min |
| 5 | Key Result: Descriptive — Perbandingan metrik throughput (RPS), rata-rata latensi, & median | Tabel statistik deskriptif mean ± std dev | 2 min |
| 6 | Key Result: Statistical — Boxplot sebaran latensi p95 & p-value uji signifikansi | Grafik boxplot & hasil uji hipotesis | 2 min |
| 7 | Discussion: Concurrency — Analisis multi-worker OS PHP vs single-thread Node.js | Diagram event loop vs process forking | 2 min |
| 8 | Limitation & Future Work — Batasan deployment server dev & ORM overhead | Slide batasan penelitian & future work | 1.5 min |
| 9 | Conclusion & Contribution — Jawaban RQ & rekomendasi arsitektural | Slide ringkasan poin kontribusi | 1 min |

Anticipatory Defense Matrix:
| Kategori | Pertanyaan Potensial | Jawaban (CER) |
|----------|---------------------|---------------|
| Problem  | Mengapa menggunakan database 100K baris? | Dataset 100K merepresentasikan beban CRUD relasional yang kompleks [C]. Benchmark umum hanya memakai DB kecil (<1K) yang gagal memicu kemacetan I/O [E]. Penggunaan dataset besar penting untuk mengevaluasi query multi-join di bawah saturasi CPU [R]. |
| Gap      | Mengapa mengunci resource container? | Penguncian resource menjamin keadilan perbandingan performa framework [C]. Docker limits `cpus: '1.0'` dan `memory: 512M` diterapkan setara di kedua grup [E]. Perilaku performa runtime hanya bisa diuji valid jika resource dibatasi secara setara [R]. |
| Method   | Mengapa menggunakan Mann-Whitney U untuk Throughput? | Distribusi data throughput Express.js melanggar asumsi normalitas [C]. Hasil uji Shapiro-Wilk menunjukkan data `reqs_rps` Express memiliki p=0.0042 [E]. Karena asumsi normalitas dilanggar, uji non-parametrik MWU lebih valid [R]. |
| Results  | Mengapa error rate Laravel mencapai 0.05% sedangkan Express 0.00%? | Laravel mengalami transient timeout akibat saturasi CPU penuh pada web server development PHP-CLI [C]. Laporan log Laravel run 10 menunjukkan terdapat 1 request gagal dari total 196 request [E]. Kegagalan tersebut dipicu batas maksimal antrean TCP ketika CPU saturasi 100% [R]. |
| Generalization | Apakah hasil ini dapat digeneralisasi langsung ke server produksi? | Hasil ini valid untuk arsitektur runtime dasar, namun generalisasi ke produksi memerlukan PM2/PM2 Cluster [C]. Tercantum dalam Bab Batasan Penelitian di laporan [E]. Evaluasi server produksi direncanakan pada future work [R]. |

Latihan:
  Latihan 1: 2026-07-08 — Penjelasan latar belakang dipercepat, fokus diperdalam ke grafik p95.
  Latihan 2: 2026-07-09 — Timing pas 15 menit, penyesuaian transisi slide hasil pengujian.
  Latihan 3: 2026-07-10 — Uji coba simulasi Q&A berjalan lancar dan siap untuk sidang.
```

---

## Latihan 1 — Slide Outline

Rencanakan presentasi 15 menit untuk riset Anda.

| # | Pesan Utama | Visual yang Digunakan | Waktu |
|---|-------------|----------------------|-------|
| 1 | Slide Judul & Konteks — Perbandingan Performa Laravel 11 vs Express.js | Halaman judul, identitas presenter | 1 min |
| 2 | Problem — Pemilihan framework berbasis bias subyektif dan tidak adanya benchmark database relasional kompleks berskala besar | Diagram motivasi, daftar isu cloud hosting | 2 min |
| 3 | Gap + RQ — Kurangnya studi terisolasi (Docker resources cap) dan pengujian signifikansi statistik | Tabel gap positioning dengan studi terdahulu | 1.5 min |
| 4 | Method Overview — Arsitektur eksperimen Docker Compose (1 CPU, 512MB RAM) dan profil k6 CRUD e-commerce | Diagram topologi sistem dan tabel komposisi query | 2 min |
| 5 | Key Result: Descriptive — Perbandingan metrik throughput (RPS), latensi rata-rata, dan median | Tabel rangkuman statistik mean ± std dev | 2 min |
| 6 | Key Result: Statistical — Boxplot sebaran latensi p95 dan p-value uji hipotesis | Boxplot grafik, visualisasi error rate | 2 min |
| 7 | Discussion: Concurrency — Penjelasan arsitektur multi-worker OS PHP vs single-thread Node.js | Diagram workflow event loop vs process forking | 2 min |
| 8 | Limitation & Future Work — Batasan deployment server dev dan overhead ORM | Slide batasan penelitian dan saran riset lanjutan | 1.5 min |
| 9 | Conclusion & Contribution — Jawaban singkat RQ dan rekomendasi cost-efficiency arsitektur cloud | Slide ringkasan poin kontribusi utama | 1 min |

**Total waktu estimasi:** 15 menit

---

## Latihan 2 — Anticipatory Defense

Prediksi 5 pertanyaan yang mungkin diajukan penguji, lalu siapkan jawaban CER.

| # | Kategori | Pertanyaan | Claim | Evidence | Reasoning |
|---|----------|-----------|-------|----------|-----------|
| 1 | *Problem* | *Contoh: Mengapa fokus kepuasan, bukan akurasi?* | *Akurasi tinggi tidak menjamin kepuasan* | *Survey: 45/100 satisfaction meski RMSE 0.87* | *Gap antara metrik teknis dan pengalaman pengguna* |
| 2 | *Method* | *Contoh: Mengapa hanya 3 dataset?* | *3 dataset mewakili variasi: small-clean, medium-clean, medium-noisy* | *Tabel karakteristik dataset di Bab Method* | *Generalisasi perlu validasi lanjut — tercatat sebagai limitasi* |
| 3 | Method | Mengapa membatasi kontainer API pada 1.0 CPU Core dan 512MB RAM? | Isolasi resource secara ketat menjamin keadilan (*fairness*) perbandingan performa framework. | Batasan Docker Compose resource limits `cpus: '1.0'` dan `memory: 512M` diterapkan sama di kedua grup. | Perilaku performa runtime di bawah saturasi kapasitas (CPU bound) hanya bisa diuji valid jika resource dibatasi secara setara. |
| 4 | Method | Mengapa menggunakan uji non-parametrik Mann-Whitney U untuk Throughput? | Distribusi data throughput Express.js melanggar asumsi normalitas. | Hasil uji Shapiro-Wilk menunjukkan data `reqs_rps` Express memiliki $p = 0.0042$ (di bawah $\alpha = 0.05$). | Karena asumsi normalitas dilanggar, uji non-parametrik Mann-Whitney U yang bebas asumsi sebaran data jauh lebih tepat secara statistik dibanding t-test biasa. |
| 5 | Results | Mengapa error rate Laravel mencapai 0.05% sedangkan Express 0.00%? | Laravel mengalami transient timeout akibat saturasi CPU penuh (100% cap) pada web server development PHP-CLI. | Laporan log Laravel run 10 menunjukkan terdapat 1 request gagal dari total 196 request. | Kegagalan tersebut disebabkan oleh batas maksimal antrean koneksi TCP pada server development ketika CPU mencapai saturasi penuh, bukan bug logic aplikasi. |

---

## Latihan 3 — Simulasi Q&A

Minta teman/kolega mengajukan 3 pertanyaan tentang riset Anda. Catat pertanyaan dan evaluasi jawaban Anda.

| # | Pertanyaan | Jawaban Saya | Evaluasi |
|---|-----------|-------------|----------|
| *Contoh* | *"Mengapa tidak membandingkan dengan metode Y?"* | *"Karena Y memerlukan dataset labeled yang tidak tersedia. Disebutkan sebagai limitasi di halaman X."* | *[✓] Direct [✓] Data-based [✓] Honest* |
| 1 | "Mengapa rata-rata latensi Laravel lebih cepat padahal Node.js/Express dikenal asinkron non-blocking?" | "Karena pada CPU saturasi penuh (100% cap) akibat query join DB berat, overhead penanganan callback asinkron Node.js menumpuk dalam satu utas, memicu head-of-line blocking. Sedangkan PHP mendistribusikan 4 worker via OS-scheduling yang lebih resilien." | [✓] Direct [✓] Data-based [✓] Honest |
| 2 | "Apakah perbedaan throughput 2.19 vs 2.72 RPS memiliki dampak praktis?" | "Ya, selisih 24.22% throughput dan ~945 ms latensi rata-rata (p < 0.001) sangat signifikan secara praktis bagi responsivitas UI backend di bawah beban puncak." | [✓] Direct [✓] Data-based [✓] Honest |
| 3 | "Kenapa Anda tidak menggunakan server tingkat produksi seperti PM2 atau Swoole?" | "Itu di luar cakupan riset baseline ini. Kami membatasi pada runtime standard default framework untuk menguji fondasi arsitektur aslinya, dan penggunaan server produksi dicatat sebagai Future Work di Bab Kesimpulan." | [✓] Direct [✓] Data-based [✓] Honest |

**Pertanyaan yang paling sulit dijawab:**
> Menjelaskan bagaimana transient lag pada server host CPU (Docker host overhead) dapat diisolasi agar tidak mendistorsi variabilitas standar deviasi antar-run.

**Apa yang perlu disiapkan lebih baik:**
> Menyiapkan visualisasi diagram arsitektur runtime Express (Event Loop thread pool) dan Laravel (Forked multi-worker pool) berdampingan untuk mempermudah penjelasan mekanisme konkurensi saat sesi tanya jawab.

---

## Refleksi

> Dari seluruh proses WS-01 sampai WS-16 — dari paradigma riset hingga presentasi — bagian mana yang paling mengubah cara Anda berpikir tentang riset? Apa satu hal yang akan selalu Anda terapkan di riset berikutnya?

**Insight terbesar:**
> Bagian yang paling membuka mata saya adalah validasi data (WS-11) dan pra-pemrosesan data (WS-13). Pemahaman bahwa setiap keputusan preprocessing bukanlah sekadar formalitas teknis biasa, melainkan keputusan riset kritis yang menentukan validitas kesimpulan ilmiah, sangat mengubah cara pandang saya. Riset bukan hanya soal mengumpulkan data sebanyak-banyaknya, melainkan bagaimana data tersebut diproses tanpa distorsi demi menjaga integritas penelitian.

**Yang akan selalu diterapkan:**
> Saya akan selalu menerapkan pengujian signifikansi statistik inferensial (seperti t-test atau Mann-Whitney U) dan visualisasi standar deviasi/error bar. Saya tidak akan lagi melaporkan perbandingan performa hanya berdasarkan nilai rata-rata tunggal (*single mean values*) tanpa melampirkan ukuran efek (*effect size*) dan rentang keyakinan (*confidence interval*) yang meyakinkan secara ilmiah.
