**Evaluasi Performa Laravel 11 dan Express.js pada REST API CRUD Kompleks di Bawah Konkurensi Tinggi**

Nurlaela Kusumandari  
Teknik Informatika, Universitas Putra Bangsa  
email: nurlaela@student.upb.ac.id

---

**ABSTRAK**

Keputusan pemilihan *framework* backend sering kali didasarkan pada preferensi subjektif tim, bukan bukti empiris terukur. Penelitian ini membandingkan performa Laravel 11 (PHP 8.4) dan Express.js 4.x (Node.js 20) secara terkontrol pada skenario REST API CRUD kompleks menggunakan dataset e-commerce relasional berisi 100.000 baris data. Pengujian dilakukan menggunakan k6 dengan 20 *Virtual Users* (VU) selama 70 detik, direplikasi 10 kali per *framework*, dalam kontainer Docker terisolasi dengan batasan sumber daya setara (1.0 CPU Core, 512MB RAM). Hasil menunjukkan Laravel 11 secara signifikan mengungguli Express.js dalam *throughput* (+24,22%), latensi rata-rata (−22,00%), dan latensi persentil ke-95 (−40,64%), yang keseluruhannya terbukti signifikan secara statistik (*p* < 0,001) dengan ukuran efek besar. Temuan ini membuktikan bahwa arsitektur *multi-process* PHP lebih resilien dibanding *single event loop* Node.js di bawah saturasi CPU saat mengeksekusi kueri relasional berat.

**Kata Kunci:** Laravel; Express.js; REST API; *Performance Testing*; k6

---

***ABSTRACT***

*Backend framework selection is often driven by team preference rather than empirical evidence. This study compares the performance of Laravel 11 (PHP 8.4) and Express.js 4.x (Node.js 20) in a controlled setting for complex REST API CRUD operations on a relational e-commerce dataset of 100,000 rows. Load testing was conducted using k6 with 20 Virtual Users (VU) for 70 seconds, replicated 10 times per framework, within isolated Docker containers with equal resource constraints (1.0 CPU Core, 512MB RAM). Results show that Laravel 11 significantly outperforms Express.js in throughput (+24.22%), average latency (−22.00%), and p95 latency (−40.64%), all statistically significant (p < 0.001) with large effect sizes. These findings demonstrate that PHP's multi-process architecture is more resilient than Node.js's single event loop under CPU saturation when executing complex relational queries.*

***Keywords:** Laravel; Express.js; REST API; Performance Testing; k6*

---

**PENDAHULUAN**

Pengembangan aplikasi *web* modern bergantung pada pemilihan *framework* *backend* yang tepat. Keputusan ini berdampak langsung pada latensi yang dirasakan pengguna akhir, kapasitas *throughput* sistem, dan biaya infrastruktur *cloud*. Namun, dalam praktiknya, pemilihan antara ekosistem PHP—seperti Laravel—dan Node.js—seperti Express.js—sering kali lebih banyak dipengaruhi oleh kebiasaan tim atau tren komunitas, alih-alih berdasarkan bukti performa yang terukur dan relevan dengan kondisi produksi nyata.

Akar permasalahannya terletak pada kelangkaan studi empiris komparatif yang komprehensif. Mayoritas *benchmark* yang beredar hanya menggunakan basis data berskala kecil (di bawah 1.000 baris) dan skenario CRUD sederhana. Penelitian sebelumnya seperti Siahaan dan Wijaya (2024) membandingkan Laravel dan Express.js menggunakan Apache JMeter, namun tidak melibatkan kueri multi-tabel relasional kompleks. Mosul, Jajuli, dan Maulana (2024) menguji kedua *framework* pada aplikasi To-Do List sederhana, yang tidak mencerminkan kompleksitas transaksi aplikasi e-commerce nyata. Pratama dan Farisi (dalam publikasinya) serta Azzahidi, Wijayanto, dan Darmawan (2025) memperluas cakupan ke bahasa lain (Go, Flask, Spring Boot), namun tetap menggunakan dataset kecil tanpa pembatasan *resource* perangkat keras kontainer secara eksplisit. Rompis dan Aji (2018) memberikan landasan awal perbandingan Node.js dan PHP, tetapi pada era *runtime* yang lebih lama tanpa konteks *containerized deployment*.

Celah penelitian (*research gap*) yang teridentifikasi meliputi: (1) tidak adanya studi komparatif dengan pembatasan *resource* perangkat keras yang ketat dan terstandar; (2) absennya skenario kueri relasional kompleks berskala menengah ke atas (>100.000 baris, multi-join lintas 5 tabel, agregasi, filter dinamis, dan paginasi); serta (3) kurangnya pembuktian signifikansi statistik melalui uji hipotesis formal pada studi komparasi *framework*.

Penelitian ini diposisikan untuk mengisi celah tersebut. Rumusan masalah yang diajukan adalah: (1) sejauh mana perbedaan performa latensi rata-rata dan *p95* antara Laravel 11 dan Express.js 4.x dalam menangani CRUD kompleks e-commerce pada dataset relasional 100.000 baris di bawah konkurensi tinggi? dan (2) bagaimana perbandingan efisiensi *throughput* (RPS) dari kedua *framework* di bawah limitasi sumber daya CPU dan RAM kontainer yang setara? Tujuan penelitian adalah menghasilkan rekomendasi pemilihan *framework* *backend* berbasis data empiris yang terkontrol dan sepenuhnya reproduksibel untuk mendukung pengambilan keputusan teknis yang lebih terukur.

---

**METODE**

Penelitian ini mengadopsi rancangan komparasi eksperimental terkontrol (*controlled experimental comparison*). Variabel independen adalah tipe *framework backend* (nominal): Laravel 11 (PHP 8.4-cli) versus Express.js 4.x (Node.js 20). Variabel dependen utama meliputi *throughput* (RPS), latensi rata-rata (*avg latency*, ms), latensi persentil ke-95 (*p95 latency*, ms), dan tingkat kegagalan (*error rate*, %). Replikasi eksperimental dilakukan sebanyak 10 *run* independen per *framework*.

Seluruh komponen sistem dijalankan dalam kontainer Docker Compose terisolasi pada *platform linux/amd64*. Pembatasan sumber daya perangkat keras diterapkan secara ketat: kontainer API (Express.js maupun Laravel) masing-masing dibatasi pada 1,0 CPU Core dan 512MB RAM, sedangkan kontainer MySQL dibatasi pada 2,0 CPU Cores dan 1.024MB RAM. *Service* Express.js menggunakan driver `mysql2/promise` dengan *connection pooling* (`DB_CONNECTION_LIMIT=10`) dan kueri raw SQL parametrik. *Service* Laravel menggunakan `DB::select` dengan driver PDO dan `PHP_CLI_SERVER_WORKERS=4` untuk konkurensi berbasis *process forking*. Semua *layer* sesi dan *caching* dinonaktifkan pada Laravel (`SESSION_DRIVER=file`, `CACHE_STORE=file`) dan kueri SQL yang dieksekusi oleh kedua *framework* dibuat identik persis, termasuk LEFT JOIN agregasi reviews lintas 5 tabel.

Dataset e-commerce relasional berisi 100.000 baris data pada 5 tabel: `categories`, `brands`, `products`, `inventory`, dan `reviews`. Skenario *load testing* k6 mensimulasikan beban kerja e-commerce realistis dengan distribusi request: 68% listing produk (kueri multi-join kompleks + filter harga & status + paginasi), 18% *view* detail produk (single product + LEFT JOIN rating), 6% *create* produk (INSERT ke `products` & `inventory`), 5% *update* produk (UPDATE ke `products` & `inventory`), dan 3% *delete* produk (DELETE ke `products`, `reviews`, & `inventory`). Konfigurasi beban menggunakan 20 Virtual Users (VU), fase *warmup* 30 detik (tidak dihitung dalam metrik), fase *ramp-up* 10 detik, dan fase *sustain* 20 detik. Seluruh 20 *run* dieksekusi otomatis menggunakan skrip PowerShell dengan jeda 5 detik antar *run*.

Data mentah log k6 berformat UTF-16 BOM diproses menggunakan utilitas Python `parse_results.py` melalui tahapan: dekode multi-*encoding*, pembersihan karakter grafik CLI, dan standardisasi unit waktu ke milidetik. Nilai *mean* dan standar deviasi dihitung dari 10 *run* per *framework*. Untuk pembuktian statistik, uji normalitas Shapiro-Wilk dilakukan terlebih dahulu. Karena data Express.js melanggar asumsi normalitas pada *throughput* (*p* = 0,0042) dan latensi rata-rata (*p* = 0,0046), digunakan uji non-parametrik Mann-Whitney U untuk kedua metrik tersebut. Untuk latensi *p95* yang memenuhi asumsi normalitas, digunakan uji parametrik Welch's *t*-test. Ukuran efek dihitung menggunakan Cohen's *d*.

---

**HASIL DAN PEMBAHASAN**

Tabel 1 menyajikan rangkuman statistik deskriptif performa dari 10 *run* independen per *framework*.

Tabel 1. Perbandingan Statistik Performa Laravel 11 vs Express.js (n=10)

| Metrik Evaluasi | Express.js (Mean ± Std) | Laravel 11 (Mean ± Std) | Selisih Performa |
|---|---|---|---|
| Throughput (RPS) | 2,19 ± 0,09 req/s | **2,72 ± 0,12 req/s** | +24,22% (Laravel unggul) |
| Avg Latency | 4.295,00 ± 181,78 ms | **3.350,00 ± 155,31 ms** | −22,00% (Laravel unggul) |
| Median Latency | **3.482,00 ± 271,62 ms** | 4.069,00 ± 214,54 ms | −16,85% (Express unggul) |
| p95 Latency | 9.825,00 ± 373,56 ms | **5.832,00 ± 365,45 ms** | −40,64% (Laravel unggul) |
| Error Rate | **0,00 ± 0,00%** | 0,05 ± 0,15% | — |

Laravel 11 unggul dalam tiga dari empat metrik utama. Express.js hanya unggul pada *median latency*, mengindikasikan pola distribusi latensi yang berbeda: Node.js memproses *request* tipikal dengan cepat namun memiliki ekor distribusi (*tail*) yang sangat panjang di bawah saturasi CPU.

Tabel 2 menyajikan hasil uji signifikansi statistik.

Tabel 2. Hasil Uji Signifikansi Statistik

| Metrik | Uji | Statistik | *p*-value | Cohen's *d* | 95% CI Selisih |
|---|---|---|---|---|---|
| Throughput (RPS) | Mann-Whitney U | U = 0,00 | 0,00018 (<0,001) | −4,87 (large) | [0,43; 0,64] req/s |
| Avg Latency | Mann-Whitney U | U = 100,00 | 0,00018 (<0,001) | 5,30 (large) | [777,27; 1.112,73] ms |
| p95 Latency | Welch's *t*-test | *t* = 22,92 | 9,13×10⁻¹⁵ (<0,001) | 10,25 (extreme) | [3.627,01; 4.358,99] ms |

Ketiga hipotesis nol (H₀) ditolak dengan signifikansi statistik yang sangat kuat (*p* < 0,001). Ukuran efek Cohen's *d* yang besar hingga ekstrem mengonfirmasi bahwa perbedaan performa ini bermakna secara praktis.

Keunggulan Laravel 11 dapat dijelaskan melalui dua mekanisme kausal. Pertama, model konkurensi: PHP 8.4 dengan 4 CLI *workers* menggunakan *forking* proses OS, sehingga saat kueri database memblokir I/O, *OS scheduler* dapat menukar proses terblokir dengan *worker* lain. Express.js mengandalkan *single event loop* yang, di bawah saturasi CPU 1,0 core, mengakibatkan *overhead* manajemen *callback* asinkron melonjak tajam. Kedua, kinerja *tail latency*: lonjakan p95 Express.js (9.825 ms vs 5.832 ms) merupakan efek *head-of-line blocking* di mana *request* yang datang belakangan terhambat oleh proses I/O kompleks yang mengantri di depan utas tunggal yang sudah jenuh.

Temuan ini sejalan dengan Siahaan dan Wijaya (2024) yang juga melaporkan keunggulan Laravel dalam latensi rata-rata, namun penelitian ini memperluas kontribusi dengan dataset 100 kali lebih besar, kueri lebih kompleks, pembatasan *resource* kontainer yang ketat, dan pembuktian statistik formal. Berbeda dengan Mosul et al. (2024) yang melaporkan keunggulan Node.js pada I/O-*bound* sederhana, penelitian ini menunjukkan bahwa keunggulan tersebut berbalik ketika kueri menjadi sangat berat dan CPU mencapai saturasi—konsisten dengan argumen bahwa *bottleneck* beban kerja berat bergeser dari I/O jaringan ke I/O database, di mana model *multi-process* PHP lebih tangguh.

---

**SIMPULAN**

Penelitian ini membuktikan secara empiris dan statistik bahwa di bawah pembatasan *resource* perangkat keras yang ketat (1,0 CPU Core, 512MB RAM) dengan beban database relasional kompleks pada dataset 100.000 baris, Laravel 11 (PHP 8.4) secara signifikan mengungguli Express.js (Node.js 20) dalam tiga metrik utama: *throughput* (+24,22%), latensi rata-rata (−22,00%), dan latensi *tail* persentil ke-95 (−40,64%), dengan signifikansi statistik *p* < 0,001 dan ukuran efek besar hingga ekstrem (Cohen's *d* = −4,87 hingga 10,25).

Implikasi praktis dari temuan ini: pada skenario *cloud hosting* dengan spesifikasi terbatas (1 vCPU / 512MB RAM) yang melayani kueri database relasional kompleks, runtime PHP 8.4 dengan konfigurasi *multi-worker* terbukti lebih efisien. Node.js lebih optimal pada skenario I/O ringan tanpa saturasi CPU. Penelitian mendatang disarankan: (1) mengevaluasi performa menggunakan *web server* tingkat produksi (Nginx + PHP-FPM / Swoole / FrankenPHP vs PM2 Node.js *cluster*); (2) membandingkan *overhead* ORM (Eloquent vs Sequelize); dan (3) melakukan pengujian pada dataset skala jutaan baris untuk menganalisis titik *break-even* skalabilitas.

---

**REFERENSI**

Azzahidi, A. S., Wijayanto, B., & Darmawan, A. (2025). Performance evaluation of backend frameworks for REST API: A comparative study of Spring Boot, Flask, Express.js, Laravel FrankenPHP, and Gin. *Jurnal Teknik Informatika (JUTIF)*, *6*(4). https://doi.org/10.52436/1.jutif.2025.6.4.4811

Mosul, E., Jajuli, M., & Maulana, I. (2024). Analisis perbandingan performa dan efektivitas framework Laravel dan Node.js dalam pengembangan web. *JITET (Jurnal Informatika dan Teknik Elektro Terapan)*, *14*(2). http://dx.doi.org/10.23960/jitet.v14i2.9264

Pratama, F., & Farisi, A. (n.d.). Analisis perbandingan kinerja backend API menggunakan PHP, Golang, dan JavaScript. *Universitas Multi Data Palembang*.

Rompis, A. C., & Aji, R. F. (2018). Perbandingan performa kinerja Node.js, PHP, dan Python dalam aplikasi REST. *Cogito Smart Journal*, *4*(1), 171–183.

Siahaan, M., & Wijaya, R. (2024). Performance comparison between Laravel and ExpressJs framework using Apache JMeter. *JITE (Journal of Informatics and Telecommunication Engineering)*, *7*(2), 545–554. https://doi.org/10.31289/jite.v7i2.10956
