# WS-15: Scientific Writing

> **Bab 15 — Penulisan Ilmiah**

---

## Ringkasan Materi

### Scientific Argument Flow

```
Problem → Gap → RQ → Method → Result → Analysis → Conclusion → Contribution
```

Paper ilmiah adalah **satu argumen utuh** dari masalah ke kontribusi. Setiap node harus terhubung logis ke node sebelum dan sesudahnya.

### Struktur IMRAD

| Section | Peran | Pertanyaan Kunci |
|---------|-------|-----------------|
| **Introduction** | Motivasi + frame | Why is this needed? |
| **Method** | Deskripsi (reproducible) | How was it done? |
| **Results** | Laporan objektif | What was found? |
| **Discussion** | Interpretasi + refleksi | What does it mean? |
| **Conclusion** | Ringkasan + kontribusi | So what? |

### Logical Flow — "Red Thread"

Setiap paragraf menjawab satu pertanyaan dan memicu pertanyaan berikutnya. Alur logis ini harus terasa di tiga level:
1. **Antar-kalimat** dalam paragraf
2. **Antar-paragraf** dalam section
3. **Antar-section** dalam paper

### Internal Consistency

Setiap elemen yang dijanjikan di Introduction harus hadir di Discussion/Conclusion.

**Consistency Matrix:**
```
           Intro  Method  Result  Discuss  Conclude
RQ1          ✓      ✓       ✓       ✓        ✓
RQ2          ✓      ✓       ✓       ✗ ←      ✓
Metrik-X     ✗      ✗       ✓ ←     ✗        ✗
```
**Masalah:** RQ2 dibahas di semua bagian kecuali Discussion. Metrik-X muncul di Result tapi tidak diperkenalkan di Method.

### Writing Quality Triad

| Kualitas | Deskripsi | Contoh Buruk → Baik |
|----------|----------|---------------------|
| **Clarity** | Dipahami sekali baca | "Performa meningkat" → "Accuracy meningkat dari 85.3% ke 89.7%" |
| **Precision** | Istilah eksak, tanpa ambiguitas | "signifikan" → "signifikan secara statistik (p=0.003, d=1.2)" |
| **Conciseness** | Setiap kata menambah informasi | Hapus kalimat redundan, filler words |

### Urutan Penulisan yang Disarankan

1. **Method & Results** — paling stabil, tulis pertama
2. **Discussion** — interpretasi berdasarkan hasil
3. **Introduction** — frame sesuai temuan aktual
4. **Abstract & Conclusion** — terakhir

### Target Jumlah Kata

| Section | Target |
|---------|--------|
| Introduction | 500–700 |
| Related Work | 700–1000 |
| Method | 800–1200 |
| Results | 500–800 |
| Discussion | 600–900 |
| Conclusion | 200–400 |

### Jebakan Kognitif

1. "Lebih panjang = lebih lengkap" → conciseness lebih berharga
2. "Introduction harus ditulis pertama" → justru ditulis terakhir
3. "Jargon teknis = lebih ilmiah" → clarity lebih penting
4. "Discussion = ringkasan Results" → Discussion = interpretasi + konteks

---

## Template A.15 — Paper Structure Checklist

```
PAPER STRUCTURE CHECKLIST

Title   : Performance Evaluation of Laravel and Express.js backend frameworks for complex CRUD operations under high concurrency
Target  : [x] Jurnal  [ ] Konferensi  [ ] Laporan

Section Check:
  [x] Abstract — masalah, metode, hasil utama, kontribusi (max 250 kata)
  [x] Introduction — konteks → gap → RQ → kontribusi → struktur paper
  [x] Related Work — concept-centric, gap positioning
  [x] Method — reproducible: desain, variabel, metrik, setup, prosedur
  [x] Results — tabel + grafik + observasi (tanpa interpretasi)
  [x] Discussion — interpretasi, perbandingan, implikasi, limitation
  [x] Conclusion — jawaban RQ, kontribusi, future work

Consistency Matrix:
  [x] RQ di Introduction = RQ di Method = RQ di Conclusion
  [x] Variabel di Method = variabel di Results
  [x] Klaim di Discussion didukung data di Results
  [x] Limitasi di Discussion di-address di Conclusion/Future Work

Writing Quality:
  [x] Clarity — mudah dipahami tanpa re-read
  [x] Precision — tidak ada istilah ambigu
  [x] Conciseness — tidak ada kalimat redundan
```

---

## Latihan 1 — Paper Outline

Buat outline paper untuk riset Anda menggunakan struktur IMRAD.

| Section | Konten Utama (2-3 kalimat) | Target Kata |
|---------|---------------------------|------------|
| Abstract | Keputusan pemilihan framework backend sering didasarkan pada asumsi subjektif. Penelitian ini mengevaluasi kinerja Laravel 11 dan Express.js secara empiris di bawah konkurensi 20 VU dengan beban CRUD kompleks pada dataset e-commerce 100.000 baris dalam kontainer Docker (1.0 CPU, 512MB RAM). Hasil menunjukkan Laravel 11 mengungguli Express.js secara signifikan (p < 0.001) dalam throughput (+24.22%), latensi rata-rata (-22.00%), dan latensi p95 (-40.64%). | 200-250 |
| Introduction | Efisiensi backend krusial untuk skalabilitas sistem dan efisiensi biaya hosting cloud. Sayangnya, benchmark yang beredar seringkali hanya menggunakan kueri sederhana dan database kecil (<1000 data) yang kurang realistis. Penelitian ini bertujuan menguji Laravel 11 (PHP 8.4) dan Express.js (Node.js 20) secara terkontrol di bawah skenario kueri multi-join kompleks pada dataset relasional 100K baris dengan pembatasan sumber daya yang ketat. | 500-700 |
| Related Work | Mengkaji penelitian terdahulu yang membandingkan runtime PHP dan Node.js pada server monolitik maupun mikroservis. Mengidentifikasi celah literatur (*literature gap*) berupa kurangnya studi komparatif dengan pembatasan resource perangkat keras yang ketat, beban database relasional kompleks berskala besar (100K+ baris), dan tidak adanya pembuktian uji signifikansi statistik. | 700-1000 |
| Method | Menggunakan desain eksperimen acak terkontrol secara terisolasi menggunakan Docker Compose (1.0 CPU Core, 512MB RAM untuk API; 2.0 CPU, 1GB RAM untuk MySQL). Skenario load testing k6 mensimulasikan 20 VU selama 70 detik (30s warmup, 40s sustain) dengan profil CRUD dinamis (68% listing multi-join, 18% show detail, 6% create, 5% update, 3% delete). Uji normalitas Shapiro-Wilk dan uji signifikansi Mann-Whitney U serta Welch's t-test digunakan untuk mengevaluasi data dari 10 runs replikasi. | 800-1200 |
| Results | Menyajikan data performa rata-rata (mean ± std dev) dari 10 runs per framework. Laravel 11 mencapai throughput 2.72 ± 0.12 RPS dengan latensi rata-rata 3350.00 ± 163.71 ms dan p95 5832.00 ± 385.22 ms, sedangkan Express.js menghasilkan 2.19 ± 0.09 RPS dengan latensi rata-rata 4295.00 ± 191.62 ms dan p95 9825.00 ± 393.76 ms. Kasus kegagalan request tercatat sangat kecil hanya pada Laravel run 10 (0.05% error rate) sedangkan Express.js memiliki 0% error rate. | 500-800 |
| Discussion | Menginterpretasikan keunggulan Laravel dari aspek model konkurensi (multi-process worker OS-scheduled PHP CLI lebih resilien menangani pemblokiran I/O dibanding single event loop Node.js di bawah saturasi CPU). Menganalisis lonjakan tail latency (p95) Express.js akibat penumpukan callback (*head-of-line blocking* pada tingkat aplikasi). Mendiskusikan batasan penelitian (seperti penggunaan development CLI server dibanding production-grade Swoole/PM2) dan implikasi arsitektural untuk cost-efficiency di cloud. | 600-900 |
| Conclusion | Laravel 11 terbukti secara statistik mengungguli Express.js secara signifikan di bawah pembatasan resource yang ketat dengan beban database relasional e-commerce yang berat. Penelitian mendatang disarankan untuk mengevaluasi overhead ORM (Eloquent vs Sequelize) dan performa pada web server produksi berskala besar. | 200-400 |

---

## Latihan 2 — Consistency Matrix

Buat consistency matrix untuk memverifikasi internal consistency paper Anda.

|  | Intro | Method | Result | Discussion | Conclusion |
|--|-------|--------|--------|-----------|-----------|
| *Contoh: RQ1* | *✓* | *✓* | *✓* | *✓* | *✓* |
| *Contoh: Metrik-X* | *✗ ←* | *✗ ←* | *✓* | *✗ ←* | *✗ ←* |
| RQ1 (Throughput) | ✓ | ✓ | ✓ | ✓ | ✓ |
| RQ2 (Latency avg & p95) | ✓ | ✓ | ✓ | ✓ | ✓ |
| Metrik utama (RPS, Latency) | ✓ | ✓ | ✓ | ✓ | ✓ |
| Variabel IV (Laravel vs Express.js) | ✓ | ✓ | ✓ | ✓ | ✓ |
| Variabel DV (RPS, Latency, Error Rate) | ✓ | ✓ | ✓ | ✓ | ✓ |
| Klaim/kontribusi (Laravel unggul +24% RPS, -22% latency) | ✓ | ✓ | ✓ | ✓ | ✓ |

**Isi setiap sel:** ✓ (ada & konsisten), ✗ (missing), ~ (ada tapi inkonsisten)

**Inkonsistensi yang ditemukan:**
> Sebelum dilakukan perbaikan, terdapat inkonsistensi internal di mana metodologi pra-pemrosesan data log (decoding multi-encoding UTF-16, standardisasi unit ke milidetik, keputusan menolak normalisasi) dan pembuktian uji signifikansi statistik (Welch's t-test dan Mann-Whitney U beserta nilai p dan Cohen's d) hanya tercantum dalam skrip parser dan dokumen worksheet, namun belum terintegrasi di dalam dokumen perencanaan analisis (Tahap 4) maupun draf akhir laporan penelitian.

**Tindakan perbaikan:**
> Melakukan sinkronisasi silang dengan memasukkan sub-bab Pra-pemrosesan Data (Data Preprocessing) serta sub-bab Uji Hipotesis Statistik ke dalam dokumen Tahap 4 (tahap-4-analisis-data.md) dan Bab 4 Laporan Penelitian Akhir (laporan-penelitian.md) secara terstruktur.

---

## Latihan 3 — Writing Quality Check

Ambil satu paragraf dari tulisan Anda (atau tulis paragraf baru) dan evaluasi kualitasnya.

**Paragraf asli:**
> Laravel 11 dan Express.js diuji performanya di Docker dengan CPU 1.0 dan RAM 512MB. Laravel 11 ternyata menghasilkan throughput yang lebih tinggi dibanding Express.js dan rata-rata waktu responnya lebih cepat saat diuji k6 dengan database MySQL yang isinya 100.000 data. Selisih latensinya cukup besar dan ini membuktikan bahwa Laravel lebih bagus jika CPU-nya mentok.

| Kriteria | Evaluasi | Perbaikan |
|----------|---------|-----------|
| Clarity | Frasa "diuji performanya" dan "selisih latensinya cukup besar" bersifat ambigu dan tidak merinci apakah mengacu pada latensi rata-rata atau tail latency (p95). Peran Docker dan MySQL kurang dijelaskan hubungannya. | Ubah menjadi deskripsi spesifik tentang "evaluasi performa" dan menyajikan selisih persentase metrik latensi rata-rata dan p95 secara eksplisit. |
| Precision | Istilah "CPU 1.0" dan "RAM 512MB" kurang presisi (tidak menyebutkan pembatasan core/kapasitas secara eksak). Istilah "lebih bagus" dan "mentok" bersifat kasual, subjektif, dan tidak ilmiah. | Ubah menjadi "pembatasan sumber daya setara (1.0 CPU Core, 512MB RAM)" dan "saturasi CPU didukung oleh arsitektur multi-worker OS-scheduled". |
| Conciseness | Penggunaan kata penghubung "dan" secara berulang di kalimat kedua membuat struktur kalimat terlalu panjang dan sulit dibaca. | Gabungkan parameter pengujian dan metrik hasil secara ringkas menggunakan struktur kalimat pasif yang padat informasi. |

**Paragraf setelah perbaikan:**
> Evaluasi performa Laravel 11 dan Express.js dijalankan secara terisolasi menggunakan kontainer Docker dengan pembatasan sumber daya setara (1.0 CPU Core, 512MB RAM). Pengujian beban menggunakan k6 pada dataset e-commerce relasional berisi 100.000 baris menunjukkan bahwa Laravel 11 menghasilkan throughput rata-rata yang signifikan lebih tinggi (+24.22%) dan latensi rata-rata yang lebih cepat (-22.00%) dibandingkan Express.js. Stabilitas performa Laravel di bawah saturasi CPU didukung oleh arsitektur multi-worker OS-scheduled yang lebih resilien dalam menoleransi pemblokiran I/O database.

---

## Refleksi

> Apa perbedaan antara menulis "tentang" riset dan menulis sebagai "argumen" riset? Bagaimana urutan penulisan (Method → Discussion → Introduction) mengubah kualitas tulisan?

> Menulis "tentang" riset cenderung bersifat kronologis deskriptif (hanya menceritakan aktivitas apa saja yang dilakukan peneliti dan hasil apa adanya seperti sebuah catatan harian). Sedangkan menulis sebagai "argumen" riset adalah membangun jembatan logika yang terstruktur demi meyakinkan pembaca: mengapa masalah tersebut bernilai penting (*problem*), di mana letak kelemahan studi terdahulu (*gap*), bagaimana desain metode kita memecahkan celah tersebut secara valid (*method*), dan bagaimana bukti empiris hasil pengujian secara kokoh mendukung kesimpulan ilmiah kita (*contribution*). Setiap bagian berfungsi sebagai premis yang tidak terpisahkan untuk membuktikan klaim utama riset.
>
> Urutan penulisan dari Method & Results -> Discussion -> Introduction -> Abstract & Conclusion mengubah kualitas tulisan secara mendasar dengan menjaga integritas data. Dengan menulis metode dan temuan mentah terlebih dahulu, pembahasan (Discussion) dan pembingkaian masalah (Introduction) akan berakar kuat pada data riil yang ada. Hal ini mencegah terjadinya *overclaiming* atau klaim spekulatif yang tidak didukung data, serta menjamin konsistensi internal yang sangat tinggi di seluruh bagian naskah ilmiah.
