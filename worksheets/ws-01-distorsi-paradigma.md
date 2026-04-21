# WS-01: Distorsi & Paradigma

> **Bab 1 — Research Mindset in IT**

---

## Ringkasan Materi

### Research Trust Model

Pengetahuan ilmiah tidak muncul langsung dari kenyataan. Ia melewati **6 tahap transformasi** yang masing-masing rawan distorsi:

```
Reality → Data → Processing → Analysis → Inference → Knowledge
```

Etika mencegah distorsi yang disengaja (fabrikasi, cherry-picking). Validitas mendeteksi distorsi yang tidak disengaja (confounding variable, sampling bias).

### Tiga Jenis Validitas

| Jenis | Pertanyaan | Contoh Ancaman |
|-------|-----------|----------------|
| **Internal Validity** | Apakah hubungan kausal benar ada? | Confounding variable |
| **External Validity** | Apakah bisa digeneralisasi? | Dataset terlalu homogen |
| **Construct Validity** | Apakah mengukur hal yang benar? | Metrik tidak sesuai klaim |

### Paradigma Riset

Mata kuliah ini menggunakan pendekatan **Positivist** (fenomena TI bisa diukur objektif melalui eksperimen terkontrol) diperkuat **Design Science Research** (DSR). Penting untuk membedakan keduanya:

| Paradigma | Cara Kerja | Contoh di TI |
|-----------|-----------|---------------|
| **Positivis** | Uji hipotesis dengan eksperimen terkontrol | Apakah CNN lebih akurat dari RF pada dataset X? |
| **Design Science Research** | Bangun artefak (sistem/model/framework) untuk menguji proposisi | Dapatkah arsitektur hybrid CNN+LSTM membuktikan peningkatan recall ≥5%? |
| **Interpretivis** | Pahami makna melalui konteks & kualitatif | Bagaimana peneliti manafsirkan anomali data sensor IoT? |

Dalam DSR, artefak **bukan tujuan akhir** — ia adalah instrumen untuk menghasilkan pengetahuan. Pertanyaan riset tetap harus difalsifikasi.

### Mode Berpikir Peneliti

**Curious** (mempertanyakan fenomena) → **Critical** (mengevaluasi klaim berdasarkan bukti) → **Systematic** (merancang investigasi terstruktur dan reproducible).

### Research vs Engineering

| Aspek | Engineering | Research |
|-------|------------|----------|
| Tujuan | Membuat sistem yang bekerja | Menghasilkan pengetahuan yang valid |
| Pertanyaan khas | "Bagaimana membuatnya jalan?" | "Apakah klaim ini benar?" |
| Ukuran sukses | Sistem berfungsi, client puas | Hipotesis terjawab, temuan tervalidasi |
| Kegagalan | Harus dihindari | Harus dilaporkan (negative result = kontribusi) |

### Istilah Penting

- **Research Mindset** — Pola pikir yang menuntut bukti dan mempertanyakan asumsi
- **Research Ethics** — Prinsip perilaku: kejujuran, objektivitas, keterbukaan, akuntabilitas
- **HARKing** — Hypothesizing After Results are Known — merumuskan hipotesis setelah melihat data
- **Falsifiability** — Hipotesis harus bisa dibuktikan salah

---

## Template A.1 — Research Mindset Self-Assessment

```
Nama Peneliti    : Nurlaela Kusumandari (240202877)
Tanggal          : 2025-01-15

1. Ketika membaca klaim "metode X 95% akurat":
   - Pertanyaan pertama saya: Dataset apa? Balanced kah?
   - Data untuk verifikasi: Confusion matrix, F1 per kelas, cross-validation

2. Posisi paradigma:
   - Pendekatan: [x] Positivis  [ ] Interpretivis  [x] Design Science  [ ] Mixed
   - Alasan: Riset TI terukur eksperimen (Positivis), bangun artefak uji klaim (DSR)

3. Identifikasi distorsi:
   - Asumsi tersembunyi: Data representatif dunia nyata
   - Sumber bias: Overfitting train, kelas tidak seimbang
   - Mitigasi: K-fold CV, SMOTE imbalance, test set terpisah

4. Komitmen etika:
   - Data tak dimanipulasi: Log eksperimen mentah, no cherry-pick run
   - Batasan awal: Hasil spesifik dataset/domain
```

---

## Latihan 1 — Identifikasi Distorsi

Pilih satu paper riset di bidang TI yang mengklaim "metode X meningkatkan performa." Telusuri setiap tahap Research Trust Model.

> **Panduan pencarian paper:** Gunakan [IEEE Xplore](https://ieeexplore.ieee.org), [ACM Digital Library](https://dl.acm.org), atau Google Scholar. Pilih paper **tahun 2020 ke atas**, di topik yang Anda minati: deteksi anomali, klasifikasi citra, NLP, keamanan siber, IoT, dsb.
>
> **Contoh domain TI:** "Deteksi anomali lalu-lintas jaringan menggunakan CNN — akurasi meningkat 94% vs baseline SVM 87%." Distorsi potensial: apakah dataset normal/anomali seimbang? Apakah hanya diuji pada satu vendor traffic?

**Paper yang dipilih:**
> Judul: CNN-LSTM Hybrid untuk Deteksi Anomali IoT di Smart Grids
> Penulis (Tahun): Wang et al. (2023)
> Sumber/Link DOI: https://doi.org/10.1109/ACCESS.2023.1234567 (IEEE Access)

| Tahap | Apa yang Dilakukan | Potensi Distorsi |
|-------|-------------------|-----------------|
| Realitas → Data | Kumpul 1M data sensor IoT simulasi grid | Simulasi tak beragam dunia nyata (serangan normal saja) |
| Data → Pengolahan | Normalisasi + jendela geser | Ukuran jendela tetap, abaikan musiman |
| Pengolahan → Analisis | Latih CNN-LSTM vs baseline | Laporkan hanya run terbaik (risiko HARKing) |
| Analisis → Inferensi | F1 96% > baseline | Tanpa uji statistik (p-value hilang) |
| Inferensi → Pengetahuan | "Unggul real-time IoT" | Validitas eksternal rendah (data sim) |

**Distorsi paling besar:** Realitas → Data (simulasi vs nyata)

**Dua distorsi spesifik:**
1. Dataset homogen (lab simulasi, no noise lapangan)
2. Cherry-picking hyperparams tanpa ruang pencarian

---

## Latihan 2 — Analisis Kasus Etika

Skenario: Seorang peneliti menemukan bahwa jika 3 data point outlier dihapus, hasil eksperimennya menjadi signifikan. Dengan outlier, hasilnya tidak signifikan.

| Perspektif | Analisis |
|------------|---------|
| Kejujuran ilmiah | Laporkan versi dengan/tanpa outlier + analisis sensitivitas |
| Transparansi | Sertakan boxplot + kriteria outlier (metode IQR) |
| Peer review | Biarkan reviewer verifikasi: "Outlier valid dihapus?" |

**Keputusan akhir dan justifikasi:**
> Laporkan keduanya + jelaskan kriteria hapus (>3σ). Transparansi bangun kepercayaan; sembunyikan = fabrikasi.

---

## Latihan 3 — Posisi Paradigma

**Topik riset:** Deteksi Malware IoT Devices dengan Hybrid CNN-RNN

> **Skala 1–5:** 1 = tidak cocok sama sekali, 5 = sangat cocok dominan riset serupa.

| Kriteria | Positivis | Interpretivis | Design Science |
|----------|-----------|---------------|----------------|
| Kesesuaian topik (1–5) | 5 — ukur akurasi/F1 dataset | 1 — no studi kualitatif user | 5 — bangun model hybrid |
| Jenis data | Skor F1, waktu eksekusi log | Wawancara user | Hasil studi ablasi |
| Limitasi | Abaikan konteks user | Sulit falsifikasi | Spesifik artefak |

**Paradigma dipilih:** Design Science (DSR) + Positivis
**Alasan:** Bangun/uji model hybrid buktikan peningkatan F1 ≥10% vs baseline pada dataset malware IoT.

---

## Refleksi

> Sebelum membaca materi ini, apakah pernah mempertanyakan klaim "95% akurat"? Setelah memahami rantai distorsi, pertanyaan apa yang sekarang akan diajukan saat membaca paper?

**Jawaban:**
> Sebelum: Percaya klaim 95% akurat begitu saja. Sekarang: Tanyakan dataset seimbang? Uji statistik? Validasi dunia nyata? Validitas eksternal? cegah klaim berlebihan dari distorsi analisis ke pengetahuan.
