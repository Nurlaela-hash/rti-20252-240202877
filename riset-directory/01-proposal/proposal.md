## **A. JUDUL**

Perbandingan Performa Laravel dan Express.js pada REST API CRUD Kompleks (100K+ rows)

## **B. RINGKASAN**

## Keputusan arsitektural dalam pemilihan *framework backend*, seperti Laravel (PHP) atau Express.js (Node.js), sering kali masih didikte oleh kebiasaan tim alih-alih bukti empiris terukur pada kondisi menyerupai produksi. Urgensi penelitian ini berangkat dari kelemahan mayoritas studi terdahulu yang hanya menggunakan basis data *dummy* berskala kecil dan operasi CRUD sederhana, sehingga hasilnya tidak cukup relevan untuk diterapkan pada pengembangan aplikasi skala menengah hingga besar.

## Penelitian ini bertujuan untuk mengevaluasi dan membandingkan performa Laravel dan Express.js secara komprehensif pada arsitektur REST API menggunakan transaksi yang lebih realistis. Intervensi pengujian mencakup operasi *multi-join* antar-tabel, penerapan filter dinamis, dan kalkulasi *pagination* menggunakan *dataset* terstruktur berisi 100.000 baris. Guna menjamin keadilan (*fairness*), kedua sistem dibangun di atas spesifikasi OpenAPI yang identik dan diisolasi dalam lingkungan *container*. Evaluasi dilakukan menggunakan alat injeksi beban k6, menyimulasikan 100 *Virtual Users* (VU) serentak selama 120 detik, didahului fase *ramp-up* 30 detik. Analisis data akan berfokus pada metrik *median response time*, kapasitas *throughput*, serta efisiensi utilitas sumber daya, yang kemudian diuji signifikansinya menggunakan metode statistik non-parametrik Mann-Whitney U.

## Luaran yang ditargetkan dari riset ini meliputi laporan teknis mendalam, repositori skrip pengujian yang sepenuhnya reproduksibel, paket *seed dataset*, serta dokumen rekomendasi pemilihan *framework* berbasis data empiris yang siap diadopsi oleh praktisi industri.

## **C. KATA KUNCI**

Laravel; Express.js; Performance testing; REST API; k6La

## **D. PENDAHULUAN**

### **D.1. LATAR BELAKANG DAN RUMUSAN MASALAH**

### Pengembang *backend*, arsitek sistem, dan pemangku keputusan teknis di berbagai organisasi kerap menghadapi dilema dalam menentukan *framework* utama. Pilihan ini berdampak langsung pada latensi aplikasi yang dirasakan pengguna, batas kapasitas penanganan trafik, dan biaya infrastruktur peladen (*server*). Gejala yang sering ditemui di lapangan adalah pemilihan teknologi—seperti antara ekosistem PHP atau Node.js—lebih banyak didasarkan pada preferensi subjektif tim atau praktik warisan (*legacy*), alih-alih bersumber dari pengukuran beban yang relevan dengan proyeksi produksi.

### Akar masalah dari situasi ini adalah kelangkaan literatur pengujian empiris yang komprehensif; bukti yang tersedia saat ini didominasi oleh studi skala kecil menggunakan data *dummy* dan skenario pengujian parsial. Pendekatan tersebut gagal memodelkan kompleksitas transaksi aplikasi modern yang padat dengan kueri bersarang (*nested queries*) dan pemrosesan logika bisnis tebal. Dampak dari kekosongan informasi ini adalah tingginya risiko organisasi melakukan pilihan *suboptimal* yang berujung pada latensi tinggi saat jam sibuk (*peak load*), inefisiensi memori, serta pembengkakan biaya penskalaan arsitektur. Oleh karena itu, penelitian ini dirumuskan untuk menghasilkan rekomendasi berbasis bukti pada konteks REST API berskala menengah (100.000 baris data) dengan beban transaksi operasional nyata. Titik tekannya bukan sekadar menampilkan deretan angka komparatif, melainkan menganalisis apakah selisih performa tersebut secara praktis cukup bermakna untuk dijadikan metrik tunggal penentu keputusan teknis.

### **D.2. PENDEKATAN PEMECAHAN MASALAH**

### Tujuan utama penelitian ini adalah melaksanakan komparasi objektif yang terkontrol antara ekosistem Laravel dan Express.js. Pertanyaan penelitian (RQ) yang diajukan adalah: *"Sejauh mana perbedaan performa antara Laravel dan Express.js dalam menangani REST API dengan operasi CRUD kompleks pada basis data 100.000 baris di bawah beban trafik tinggi?"* Hipotesis awal mengasumsikan adanya disparitas terukur pada efisiensi pemrosesan asinkron Node.js berbanding eksekusi sinkron PHP.

**Masalah inti (satu kalimat):** Keputusan pemilihan framework backend sering didasarkan pada kebiasaan, bukan bukti empiris performa, sehingga ada risiko memilih teknologi yang menghasilkan latensi tinggi atau biaya infrastruktur berlebih pada beban produksi.

### Pendekatan pemecahan masalah yang diusulkan berupa eksperimen komparatif (*controlled comparison*). Seluruh variabel lingkungan—perangkat keras pendukung, topologi jaringan, hingga versi basis data—dijaga agar tetap identik. Analisis akan difokuskan secara kaku pada metrik utama yang telah dipra-tetapkan guna mencegah bias interpretasi di tengah eksekusi eksperimen. *Fairness* dipastikan melalui pemanfaatan data *seeding* yang sama, konfigurasi wadah isolasi yang homogen, dan upaya optimasi fitur (*tuning effort*) yang setara untuk kedua teknologi. Strategi pelaksanaannya mencakup orkestrasi artefak berbasis spesifikasi API tunggal, pembebanan berulang terotomatisasi dengan k6, ekstraksi latensi jaringan, serta pengujian statistik presisi. Apabila ditemukan diskrepansi performa yang tajam, studi ablasi terarah—seperti mematikan fungsi ORM atau manipulasi *caching*—akan diterapkan untuk membedah akar *bottleneck* secara fundamental.

### **D.3. STATE OF THE ART DAN KEBARUAN**

### Mayoritas literatur komparasi *framework web* periode 2018–2025 telah memanfaatkan instrumen standar seperti JMeter atau k6, dengan fokus pada *response time* dan utilitas CPU. Namun, pola berulang yang menjadi keterbatasan (*limitation*) mendasar studi terdahulu adalah pengujian yang terlalu terisolasi pada lingkungan ideal tanpa interaksi rasionalitas data yang valid. Celah penelitian (*valid gap*) terletak pada absennya parameter evaluasi yang memodelkan interaksi basis data riil, seperti penggunaan kueri relasional lintas-tabel (*multi-join*), penerapan agregasi, dan kalkulasi *pagination* berbasis *offset* pada koleksi data berskala menengah (\>100.000 baris).

### Penelitian ini memosisikan diri secara presisi untuk mengisi celah tersebut dengan menempatkan skenario pengujian pada titik ekuilibrium antara purwarupa sederhana dan arsitektur *enterprise*. Kebaruan (*novelty*) yang diusulkan bukan sekadar terletak pada pembesaran skala volumetrik *dataset* atau penambahan beban *endpoint*, tetapi berpusat pada penegakan integritas metodologi eksperimental yang membekukan setiap parameter ke dalam berkas konfigurasi absolut. Hal ini memastikan jaminan reprodusibilitas data secara utuh, sebuah aspek krusial yang kerap luput pada naskah *benchmarking* pendahulu.

### **D.4. PETA JALAN PENELITIAN**

#### Peta jalan direkayasa secara sekuensial agar setiap tahapan metodologi menghasilkan artefak tervalidasi sebelum fasa komparasi dieksekusi:

1. Persiapan Fundamental: Perancangan skema relasional, spesifikasi OpenAPI, dan injeksi *seed data* sintetik MySQL berkapasitas 100.000 baris.  
2. Implementasi Layanan: Pemrograman *endpoint* ekuivalen pada Laravel 11 (berbasis Eloquent) dan Express.js 4.x (berbasis Sequelize), dilanjutkan validasi logika respons.  
3. Isolasi Lingkungan: Standardisasi kontainerisasi via Docker Compose serta penguncian ketat seluruh rantai dependensi eksternal (*freeze deps*).  
4. Eksperimentasi Kinerja: Pelaksanaan fase pemanasan (*warmup*) diikuti 3 repetisi independen per *framework* menggunakan k6; pencatatan agresif metrik perangkat keras; serta eksekusi ablasi apabila temuan mendesak.  
5. Sintesis Analitik: Pengolahan data statistik pra-terdaftar, identifikasi distribusi latensi, serta penelusuran log sistem.  
6. Diseminasi Final: Kodifikasi wawasan menjadi draf laporan teknis terstruktur dan penyusunan rekomendasi arsitektur perangkat lunak.

## **E. METODE**

### **E.1. Desain Penelitian dan Unit Analisis**

Penelitian ini mengadopsi rancangan komparasi eksperimental terkontrol (*controlled experimental comparison*). Unit analisis utamanya adalah instansiasi fungsional layanan REST API yang beroperasi penuh untuk tiap *framework*. Research Question (RQ) operasional: Apakah penggunaan Express.js menghasilkan *response time*, *throughput*, dan konsumsi sumber daya yang berbeda secara signifikan bila dikontraskan dengan Laravel saat mengeksekusi operasi REST API berdaya komputasi tinggi pada basis data 100.000 baris dengan konkurensi aktif 100 pengguna?  
 
Unit analisis, populasi, dan sampel:  
- Unit analisis: instansiasi layanan REST API ― yaitu satu deployment container yang menjalankan satu implementasi lengkap dari spesifikasi OpenAPI (satu untuk Laravel, satu untuk Express).  
- Populasi: semua kemungkinan implementasi REST API yang mematuhi spesifikasi OpenAPI untuk kasus CRUD kompleks pada dataset besar.  
- Sampel eksperimental: dua implementasi yang dibangun dan diinstrumentasi (Laravel 11 + Eloquent; Express.js 4.x + Sequelize). Observasi yang dikumpulkan adalah level-run agregat (mis. median response time per run) dan level-per-request (untuk analisis distribusi).  
- Replikasi eksperimental: tiap kondisi diuji sebanyak minimal 3 run independen (warmup sebelumnya); tiap run mensimulasikan 100 VU selama 120 detik dengan ramp-up 30 detik.

Hipotesis yang dapat diuji (terkait RQ dan terukur):  
Primary hypotheses — diuji pada level run (agregat median/p95) menggunakan uji Mann-Whitney U:  
- H0_RT: Median response time(`median_response_time`) Express = Median response time Laravel.  
- H1_RT: Median response time Express ≠ Median response time Laravel; efek praktis dianggap bila perbedaan ≥ 15% (dua sisi) dan p-value < 0.05.  

- H0_TP: Throughput (RPS) Express = Throughput Laravel.  
- H1_TP: Throughput Express ≠ Throughput Laravel; efek praktis dianggap bila perbedaan ≥ 15% dan p-value < 0.05.  

Secondary hypotheses — resource usage:  
- H0_CPU: Mean CPU% (container) Express = Mean CPU% Laravel.  
- H1_CPU: Mean CPU% Express ≠ Mean CPU% Laravel.

Catatan: semua hipotesis diuji eksplisit pada metrik yang didefinisikan di bagian Variabel; ambang praktis 15% dipilih sebagai ukuran signifikansi teknis yang relevan bagi pengambilan keputusan arsitektural.

### **E.2. Variabel, Metric, Instrumen, dan Data**

### Arsitektur pengukuran dalam pengujian komparatif ini dikelompokkan menjadi:

* Variabel Independen (IV): Tipe ekosistem *framework backend* (Skala Nominal), secara spesifik Laravel 11 (PHP 8.2 \+ Eloquent) dipertentangkan dengan Express.js 4.x (Node.js 18 \+ Sequelize).  
* Variabel Dependen Utama (Primary DV): *Median Response Time* (ms), *p95 Response Time* (ms), *Throughput* aplikasi (*Request Per Second* sukses), dan Tingkat Kegagalan/ *Error Rate* (%).  
* Variabel Dependen Sekunder (Secondary DV): Alokasi Siklus CPU (%), Konsumsi Memori Aktif (MB), serta sebaran rentang latensi (*p50/p90/p99*).  
* Variabel Kontrol (CV): Dimensi basis data (seeding 100.000 baris, 5 tabel berelasi silang), profil beban kerja statis (100 VU, *ramp-up* 30s, dipertahankan 120s), lapisan logika (*multi-join*, penyaringan khusus, kalkulasi ukuran halaman), arsitektur *host* peladen (Docker Ubuntu), dan isolasi lapisan *caching*.

Definisi variabel yang lebih eksplisit:  
- Independent Variable (IV): `framework` (nominal) dengan dua level: `Laravel` dan `Express`. Manipulasi: deploy implementasi yang mengikuti spesifikasi OpenAPI, dengan konfigurasi runtime terstandar.  
- Primary Dependent Variables (Primary DVs, diukur per run):  
  - `median_response_time` (ms): median durasi HTTP request sukses per run.  
  - `p95_response_time` (ms): 95th percentile durasi HTTP request per run.  
  - `throughput_rps` (requests per second): rata-rata RPS sukses selama run.  
  - `error_rate` (%): persentase request yang gagal (HTTP 5xx/4xx tergantung definisi) selama run.
- Secondary Dependent Variables (Secondary DVs):  
  - `cpu_usage_pct` (mean & max per container) — persentase CPU yang dipakai container server aplikasi selama run.  
  - `mem_usage_mb` (mean & max) — penggunaan memori residu aplikasi.  
  - `latency_distribution` (p50/p90/p99) — untuk memeriksa tail latency.

Instrumen dan agregasi: semua metrik dikumpulkan oleh k6 (latency, throughput, checks), dan oleh prometheus/docker-stats untuk metrik resource; agregasi primer dilakukan pada level run (median/p95/mean) dan kemudian dianalisis across-replicates menggunakan uji non-parametrik.

Instrumen: Perangkat orkestrasi k6 untuk pembebanan HTTP sintetik, skrip otomatisasi terminal (top/ps) guna perekaman metrik perangkat keras, MySQL *slow query log*, dan konfigurasi absolut Docker Compose. Justifikasi Metrik: *Median* dan rentang persentil ke-95 dipilih secara khusus karena paling merepresentasikan anomali lambatnya respons (*tail latency*) yang langsung dirasakan pengguna akhir, sedangkan pemantauan sumber daya krusial untuk proyeksi estimasi pembiayaan *cloud computing*.

### **E.3. Skenario dan Prosedur Pengujian**

### Fase perbandingan dilaksanakan dengan alur ketat untuk menihilkan anomali lingkungan eksternal:

1. Membangun topologi kontainer Docker yang identik secara hierarkis (satu sistem operasional per eksekusi) bersanding dengan mesin MySQL 5.7 terisolasi yang menampung 100.000 baris data valid.  
2. Mengeksekusi simulasi *warmup* ringan berdurasi 2 menit untuk membangun koneksi kolam basis data (*connection pooling*) dan mengaktifkan translasi JIT/OPcache (Data tidak dimasukkan dalam agregat akhir).  
3. Memerintahkan instrumen k6 menyuntikkan profil beban progresif: *ramp-up* linear selama 30 detik menuju puncak 100 VU, lalu dipertahankan secara konstan 120 detik. Seluruh matriks tanggapan HTTP diekspor dalam format JSON. Eksekusi ini diulang murni sebanyak 3 kali (*independent runs*) per kondisi uji.  
4. Menjalankan skrip *sampling* sumber daya perangkat keras secara asinkron dengan interval 100ms setiap uji berlangsung sembari menghimpun *slow query log* dari basis data.  
5. Melakukan ekstraksi parametrik dari korpus JSON; mengakumulasikan nilai *median*, p95, dan kalkulasi distribusi persentase galat.  
6. Memproses agregat menggunakan uji normalitas Shapiro-Wilk; pengalihan ke uji komparasi non-parametrik Mann-Whitney U bila ditemui sebaran non-normal; pencatatan p-value, ukuran efek (*effect size*), dan *Confidence Interval* (CI).  
7. (Fase Ablasi Opsional): Eksekusi ulang memotong lapisan ORM (beralih ke *raw SQL*) dan/atau melepas mekanisme pelacakan memori *framework* guna memastikan kausalitas performa murni secara struktural.

### 

### **E.4. Artifact, Setup, atau Kesiapan Implementasi**

### Seluruh artefak yang difabrikasi dalam riset ini berkedudukan eksklusif sebagai perangkat instrumen uji. Komposisi kesiapan sistem meliputi:

* Repositori Terkontrol: Pemisahan mutlak basis kode service-laravel/ dan service-express/, ditautkan secara ketat pada dokumentasi sentral antarmuka OpenAPI.  
* Perangkat Orkestrasi: Konstruksi mandiri skrip basis data seed.sql berisi 100.000 entitas, algoritma injeksi k6, pelacak memori sistem berbasis *shell*, serta arsitektur jaringan pada docker-compose.yml.  
* Environment Terstandar: Lapisan operasi menggunakan Linux (Ubuntu) dalam *container* terpisah; modifikasi *tuning* diseragamkan antara Nginx/PHP-FPM (pada kubu Laravel) dan PM2 Runtime/Node.js 18 (pada kubu Express.js). Rantai *library* pihak ketiga dilumpuhkan dari pembaruan via *lockfile*.

Berkas luaran mentah berupa k6 JSON dan log CSV dirancang kompatibel untuk diimpor secara instan ke *Jupyter Notebook* analitik guna manipulasi data lanjutan.

### **E.5. Teknik Analisis, Asumsi, dan Validitas**

Pertanggungjawaban integritas metrik diolah menggunakan prosedur inferensial berlapis:

* Desain Analisis Statistik: Konversi data mentah menjadi tabulasi deskriptif (*median*, *Interquartile Range*/IQR, dan ekuilibrium p95), divisualisasikan melalui *boxplot*. Pasca uji normalitas (Shapiro-Wilk), evaluasi perbedaan signifikansi dieksekusi dengan uji Mann-Whitney U (α=0,05). Batas penetapan signifikansi praktis dipatok kaku pada selisih margin intervensi sebesar 15% pada *response time*.  
* Asumsi Dasar: Model arsitektur asimetris latensi jaringan (*skewed tail distribution*) mengharuskan prioritas penuh pada pendekatan uji non-parametrik. Kestabilan matriks dijamin melalui ekuivalensi minimum replikasi 3 putaran.  
* Peta Ancaman & Mitigasi Validitas:  
  * *Internal:* Bias dari latensi inisialisasi awal (*cold start*) dimusnahkan via fase *warmup*. Distorsi performa akibat ketidaksejajaran *hardware* diisolasi mutlak melalui limitasi *Docker Host*.  
  * *Construct:* Parameter pengukuran difiksasi menggunakan nilai *serverTime* bawaan, mendisrupsi durasi negosiasi proksi jaringan (*network jitter*) agar kemurnian durasi proses komputasi terjaga.  
  * *External:* Klaim generalisasi temuan ditahan ketat eksklusif pada batas klaster data berskala 100K baris. Pengecualian disematkan pada sistem pangkalan data skala hiper atau desain terdistribusi multi-klaster.

## **F. HASIL YANG DIHARAPKAN**

Luaran spesifik yang dijanjikan dalam investigasi komparatif ini dipetakan secara realistis sebagai berikut:

* Bukti Validasi Konkret: Dokumen pembuktian kuantitatif mengenai keunggulan praktis salah satu *framework* pada metrik pemrosesan kritikal (*median response time* dan penanganan *throughput* RPS), secara spesifik saat mengeksekusi operasi relasional 100.000 baris dengan pembebanan serentak 100 VU.  
* Pemetaan Kaustik Bottleneck: Dokumentasi isolasi faktor teknis yang memicu anjloknya performa, seperti deteksi inefisiensi prapemrosesan relasional Eloquent vs Sequelize, beban manajemen siklus koneksi, maupun friksi blokade I/O, dikonfirmasi sah melalui metodologi ablasi kueri.  
* Produk Repositori Terbuka: Pelepasan artefak reproduksibel paripurna berisi basis kode sumber, skema injeksi relasional (seed dataset), konfigurasi automasi k6, beserta *Jupyter Notebook* siap pakai untuk kepentingan audit komunitas pengembang perangkat lunak.  
* Rekomendasi Strategis Berbasis Data: Panduan komprehensif bagi para teknokrat dan arsitek perangkat lunak guna melandasi pengambilan keputusan pergeseran tumpukan teknologi yang divalidasi dengan kalkulasi skalabilitas ekonomis industri riil.

## **G. JADWAL PENELITIAN**

| No | Nama kegiatan | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 |
| :---- | :---- | :---- | :---- | :---- | :---- | :---- | :---- | :---- | :---- |
| 1 | Identifikasi komparasi permasalahan, ulasan literatur, dan penyusunan gap riset |  |  |  |  |  |  |  |  |
| 2 | Desain struktur spesifikasi kontrak OpenAPI dan rasionalisasi skema basis data |  |  |  |  |  |  |  |  |
| 3 | Konstruksi skrip automasi inisialisasi 100.000 baris data sintetik dan isolasi Docker |  |  |  |  |  |  |  |  |
| 4 | Pemrograman logika endpoint terpadu (routing, ORM) pada ekosistem Laravel & Express.js |  |  |  |  |  |  |  |  |
| 5 | Orkestrasi eksekusi lingkungan: Pembebanan paralel sintetik via k6 dan pelacakan metrik host |  |  |  |  |  |  |  |  |
| 6 | Integrasi data komputasi, penyaringan normalitas statistik, dan uji ablasi (apabila terpicu) |  |  |  |  |  |  |  |  |
| 7 | Penyusunan draf analisis akhir, perakitan dokumentasi repositori teknis, dan finalisasi laporan usulan |  |  |  |  |  |  |  |  |

## **H. DAFTAR PUSTAKA**

Siahaan, M., & Wijaya, R. (2024). Performance comparison between Laravel and ExpressJs framework using Apache JMeter. JITE (Journal of Informatics and Telecommunication Engineering), 7(2), 545–554.  
Pratama, F., & Farisi, A. (n.d.). Analisis perbandingan kinerja backend API menggunakan PHP, Golang, dan JavaScript.  
Mosul, E., Jajuli, M., & Maulana, I. (2024). Analisis perbandingan performa dan efektivitas framework Laravel dan Node.js dalam pengembangan web. JITET (Jurnal Informatika dan Teknik Elektro Terapan), 14(2).  
Azzahidi, A. S., Wijayanto, B., & Darmawan, A. (2025). Performance evaluation of backend frameworks for REST API: A comparative study of Spring Boot, Flask, Express.js, Laravel, FrankenPHP, and Gin. Jurnal Teknik Informatika (JUTIF), 6(4).