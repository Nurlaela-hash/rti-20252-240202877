# WS-02: Problem Statement

> **Bab 2 — Problem Formulation & System Context**

---

## Ringkasan Materi

### Problem Formation Model

Masalah riset melewati 5 tahap transformasi. Melompat langsung dari Reality ke Variable adalah kesalahan paling umum.

```
Reality → Observed Issue (Symptom) → Diagnosed Problem (Root Cause)
→ Researchable Problem (Scoped) → Measurable Variable (Operationalized)
```

### Topic ≠ Problem ≠ Research Problem

| Level | Contoh | Status |
|-------|--------|--------|
| **Topik** | Keamanan IoT | Terlalu luas, tidak bisa diuji |
| **Problem** | MQTT tidak terenkripsi | Spesifik tapi belum riset |
| **Research Problem** | Belum ada studi membandingkan overhead TLS 1.3 vs DTLS pada MQTT di IoT RAM < 64KB | Bisa dirancang eksperimennya |

### Symptom vs Root Cause

Apa yang diamati (gejala) ≠ mengapa terjadi (akar masalah). Gunakan **5 Whys** atau **Fishbone Diagram** untuk menggali.

Contoh: "User meninggalkan checkout" (symptom) → "Waktu loading > 8 detik karena API call sequential" (root cause).

### System Thinking

Setiap masalah riset TI harus terikat pada komponen sistem: **Input → Process → Output → Outcome → Constraints → Stakeholders**.

### Problem Quality Check

Masalah riset yang layak harus memenuhi 5 kriteria:
- **Clarity** — Satu orang membaca akan paham
- **Measurability** — Ada metrik kuantitatif
- **Relevance** — Penting untuk domain
- **Testability** — Bisa gagal (falsifiable)
- **Impact** — Ada kontribusi jika terjawab

### Research vs Engineering

| Aspek | Engineering | Research |
|-------|------------|----------|
| Tujuan | Menyelesaikan masalah (*solve*) | Memahami dan membuktikan (*understand & prove*) |
| Masalah | Bug, error, fitur belum ada | Gap dalam pengetahuan |
| Scope | Selesaikan semua yang perlu | Batasi agar bisa dibuktikan |
| Output | Working system | Evidence, paper, replicable findings |

### Istilah Penting

- **Problem Statement** — Formulasi tertulis: konteks sistem + gap + dampak + justifikasi
- **System Context** — Deskripsi lengkap: input, proses, output, outcome, constraints, stakeholders
- **Problem Drift** — Masalah "bermutasi" dari pendahuluan ke metodologi karena statement awal tidak presisi
- **Solution-First Thinking** — Memulai dari solusi tanpa masalah yang jelas — berbahaya dalam riset
- **Operational Definition** — Definisi variabel yang cukup jelas agar peneliti lain bisa mengukur hal yang sama

---

## Template A.2 — Problem Statement Builder

```
PROBLEM STATEMENT BUILDER

Domain & Konteks
  Domain   : Keamanan Siber IoT
  Konteks  : Perangkat IoT resource-constrained (RAM <64KB) rentan malware

System Context
  Input       : Network traffic packets dari IoT devices
  Process     : Hybrid CNN-RNN classification
  Output      : Malware/benign label + confidence score
  Outcome     : Real-time threat mitigation
  Constraints : Low compute (edge devices), imbalanced data
  Stakeholders: IoT manufacturers, cybersecurity firms, end-users

Fenomena → Problem
  Fenomena yang diamati             : Lonjakan malware IoT attacks 300% (2023)
  Gejala (symptom) yang terukur     : F1-score models existing <0.82 on IoT datasets
  Masalah yang didiagnosis          : Feature extraction tidak capture temporal patterns
  Masalah riset (researchable)      : Hybrid CNN-RNN vs baselines pada low-resource IoT malware
  Variabel yang terukur             : F1-score, latency (ms), RAM usage (KB)

Problem Quality Check
  [x] Clarity — Apakah satu orang membaca akan paham?
  [x] Measurability — Apakah ada metrik kuantitatif?
  [x] Relevance — Apakah penting untuk domain?
  [x] Testability — Apakah bisa gagal?
  [x] Impact — Apakah ada kontribusi jika terjawab?

Problem Statement (1 paragraf):
  Dalam ekosistem IoT resource-constrained, deteksi malware existing gagal capture temporal behavior network traffic (F1<0.82), menyebabkan false negatives tinggi. Gap: belum ada hybrid CNN-RNN diuji pada datasets IoT real seperti IoT-23 dengan constraints RAM<64KB. Research problem: apakah hybrid model tingkatkan F1 ≥0.90 vs baselines tanpa tambah latency >20ms?
```

---

## Latihan 1 — Dari Topik ke Masalah Riset

Pilih satu topik di bidang TI yang diminati. Transformasikan melalui 5 tahap Problem Formation Model.

**Topik awal:** Keamanan IoT

| Tahap | Hasil |
|-------|-------|
| Realitas | Serangan malware IoT naik 300% tahun 2023 |
| Gejala (Symptom) | F1 deteksi <0.82 pada dataset IoT (IoT-23) |
| Masalah Diagnosis (Root) | Fitur statis tak tangkap pola temporal |
| Masalah Riset | Hybrid CNN-RNN vs RF/SVM low-resource IoT |
| Variabel Ukur | F1-score, latency inferensi (ms), RAM (KB) |

**Terjebak solution-first?** [ ] Ya / [ ] Tidak
> Tidak, mulai data lonjakan → spesifik gap terukur.

---

## Latihan 2 — System Context Decomposition

Gambarkan konteks sistem dari masalah riset di Latihan 1.

| Komponen | Deskripsi |
|----------|----------|
| Masukan | Paket jaringan IoT (pcap, dataset IoT-23) |
| Proses | Preprocess → fitur spasial CNN → temporal RNN → klasifikasi |
| Keluaran | Skor probabilitas malware (0-1) |
| Hasil | Blokir traffic berbahaya <50ms |
| Kendala | Edge: CPU 1GHz, RAM 64KB, no GPU |
| Pemangku | Vendor IoT (Xiaomi), tim keamanan, konsumen |

**Komponen paling relevan:** Proses (model hybrid low-resource).

---

## Latihan 3 — Problem Quality Check

Evaluasi problem statement yang sudah dibuat menggunakan 5 kriteria.

| Kriteria | Skor (1-5) | Justifikasi |
|----------|-----------|-------------|
| Kejelasan | 5 | Variabel/metrik/dataset spesifik |
| Ukurable | 5 | F1/latensi/RAM angka |
| Relevansi | 5 | Malware IoT krusial 2024 |
| Testable | 5 | Gagal jika F1 tak naik |
| Dampak | 4 | Deploy edge jika sukses |

**Skor total:** 24 / 25

**Problem statement final (1 paragraf):**
> Pada jaringan IoT RAM <64KB, model malware konvensional (RF/SVM) gagal tangkap pola temporal traffic (F1 <0.82 IoT-23), menyebab false negative tinggi & delay. Problem riset: hybrid CNN-RNN tingkatkan F1 ≥0.90, latency <20ms, RAM <60KB vs baseline, uji dataset IoT real.

---

## Refleksi

> Bandingkan "masalah" yang biasa ditemui saat coding (bug, error) dengan masalah riset. Apa perbedaan fundamental dalam cara mendefinisikan dan mendekati keduanya?

**Jawaban:**
> Bug coding: perbaiki teknis cepat, sukses = jalan. Masalah riset: gap ilmu, batas falsifiable, sukses = bukti valid walau H0 benar. Riset perlu cek kualitas (jelas/ukur), engineering fokus fungsi.
