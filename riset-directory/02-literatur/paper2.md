```
JITE, 7 (2) January 202 4 ISSN 2549-6247 (Print) ISSN 2549 - 6255 (Online)
```
(^)

## JITE (Journal of Informatics and

## Telecommunication Engineering)

```
Available online http://ojs.uma.ac.id/index.php/jite DOI : 10.31289/jite.v7i2.
```
```
Received: 17 October 2023 Accepted: 24 November 2023 Published: 31 January 2024
```
(^)

### Performance Comparison Between Laravel and ExpressJs Framework

### Using Apache JMeter

```
Mangapul Siahaan1), Ricky Wijaya2)
1 ,2)Sistem Informasi, Fakultas Ilmu Komputer, Universitas Internasional Batam, Indonesia
```
```
*Coresponding Email: mangapul.siahaan@uib.edu
Abstrak
Pengembangan aplikasi web modern membutuhkan pemilihan framework yang optimal untuk memastikan efisiensi
dan kinerja yang maksimal. Penelitian ini membandingkan dua framework terkemuka, Laravel (menggunakan
bahasa PHP) dan Express.js (menggunakan JavaScript), dalam konteks pembangunan RESTful API. Tujuan penelitian
ini adalah untuk membandingkan kinerja Laravel dan Express.js melalui pengujian kinerja, dengan fokus pada
respons API terhadap akses data mahasiswa dalam basis data MySQL. Metode penelitian yang digunakan adalah
pengujian kinerja yang melibatkan skenario pengujian yang realistis dan metrik kinerja seperti waktu respons Hasil
penelitian memberikan pemahaman yang mendalam tentang keunggulan dan kelemahan kinerja Laravel dan
Express.js dalam pengembangan API. Penelitian ini membantu pengembang memilih framework yang sesuai dengan
kebutuhan proyek, mempertimbangkan efisiensi dan kecepatan dalam pengembangan RESTful API. Hasil pengujian
menunjukkan keunggulan framework Laravel dengan waktu respon rata-rata 1745,7 ms lebih cepat dibandingkan
Express.js. Dengan demikian Laravel lebih cocok untuk aplikasi dengan akses pengguna bersamaan yang tinggi,
sementara Express.js menjadi pilihan yang baik untuk aplikasi dengan jumlah pengguna simulasi yang lebih rendah.
Pemilihan framework harus mempertimbangkan baik kecepatan respons maupun kebutuhan aplikasi yang spesifik
dan memperhitungkan spesifikasi server yang digunakan. Implikasi penelitian ini dapat membantu pengembang
mengoptimalkan pengembangan aplikasi web, meningkatkan efisiensi, dan memastikan kinerja maksimal sesuai
dengan tujuan proyek.
Kata Kunci: Laravel, Express.js, Pengujian Kinerja, API.
```
```
Abstract
Modern web application development requires selecting an optimal framework to ensure maximum efficiency and
performance. This research compares two leading frameworks, Laravel (using PHP) and Express.js (using JavaScript),
in the context of building RESTful APIs. The purpose of this research is to compare the performance of Laravel and
Express.js through performance testing, with a focus on the API response to student data access in the MySQL database.
The research method used is performance testing which involves realistic test scenarios and performance metrics such
as response time. The research results provide a deep understanding of the performance advantages and disadvantages
of Laravel and Express.js in API development. This research helps developers choose a framework that suits project
needs, considering efficiency and speed in developing RESTful APIs. The test results show the superiority of the Laravel
framework with an average response time of 1745.7 ms faster than Express.js. This Laravel is better suited for
applications with high concurrent user access, while Express.js is a good choice for applications with a lower number of
simulated users. Framework selection must consider both response speed and specific application needs and consider
the specifications of the server used. The implication of this research can help developers optimize web application
development, increase efficiency, and ensure maximum performance according to the project goals.
Keywords: Laravel, Express.js, Performance Testing, API
```
```
How to Cite : Siahaan, M., & Wijaya, R. (2024). Performance Comparison Between Laravel and ExpressJs Framework
```
Using Apache JMeter. JITE (Journal of Informatics and Telecommunication Engineering), 7(2), 54 5 - (^554)

#### I. PENDAHULUAN

Pada era saat ini, perkembangan aplikasi berlangsung dengan pesat, seiring dengan evolusi dunia
framework web dari situs web statis pada masa awal menjadi aplikasi web yang dinamis dan interaktif saat


ini. Dengan terus berkembangnya teknologi pengembangan web, banyak pengembang yang menghadapi
kesulitan dalam memilih framework terbaik. Salah satu tantangan yang dihadapi adalah banyaknya aplikasi
yang tersedia di berbagai platform. Oleh karena itu, integrasi data menjadi sangat penting untuk
menghindari duplikasi data pada aplikasi yang berjalan di platform yang berbeda (Verma, 2022).
Application Programming Interface (API) adalah antarmuka yang dibangun oleh pengembang sistem
sehingga beberapa atau semua fungsi sistem dapat diakses secara rapi dan terprogram. API juga sering
dianggap sebagai suatu cara untuk membuat komunikasi antara komponen perangkat lunak dalam
mengirim data. Selain itu, fungsi dari API sendiri adalah memfasilitasi penggunaan teknologi tertentu saat
membuat perangkat lunak atau aplikasi untuk pengembang (Falahi, 2019). Selain dapat menukar data, API
juga sangat membantu dalam kecepatan proses development terutama di bagian backend.

Adapun salah satu teknologi yang berkembang pesat di zaman sekarang yaitu Representational State
Transfer (REST). REST merupakan suatu bentuk implementasi web service dengan konsep perpindahan
antar state (Utomo et al., 2020). State yang disebutkan di sini dapat dijelaskan jika browser membuat
permintaan web, server akan mengirim state halaman web saat ini ke browser (Syafrial & Teguh, 2019).
RESTful termasuk arsitektur API yang cukup terkenal. RESTful API sendiri menyediakan antarmuka
seragam untuk membuat, membaca, memperbarui, dan menghapus (CRUD) suatu sumber daya. Sebuah
sumber daya umumnya diidentifikasi oleh URI HTTP, dan operasi CRUD biasanya dipetakan ke metode
HTTP POST, GET, PUT, dan DELETE pada URI sumber daya tersebut (Viglianisi & Dallago, 2020). RESTful
API juga terbukti kecepatannya dalam mentransfer data lain yang serupa (Fani et al., 2021). Saat
membangun RESTful API, Ada banyak bahasa dan kerangka kerja pemrograman yang dapat dipilih untuk
mengembangkan RESTful API. Pemilihan bahasa pemrograman dalam pengembangan RESTful API sangat
signifikan karena bisa memengaruhi kinerja server, seperti waktu respons, penggunaan CPU, dan
penggunaan memori. Oleh karena itu, sangat penting untuk memilih bahasa pemrograman yang sesuai guna
mencapai kinerja optimal dalam pengembangan aplikasi.

Sudah banyak sekali bahasa pemrograman dan framework yang bermunculan untuk pengembangan
RESTful API. Dan ada beberapa bahasa pemrograman yang cukup terkenal dan banyak digunakan. Salah satu
framework yang banyak digunakan programmer adalah Laravel, Laravel merupakan kerangka kerja
berbasis web dengan bahasa pemrograman PHP dan open source. PHP sendiri adalah Hypertext Pre-
processor adalah bahasa scripting yang terkenal dan sering dikaitkan dengan pengembangan web, serta
digunakan dalam berbagai bidang lainnya. Menurut w3techs.com, PHP adalah bahasa scripting yang paling
umum digunakan di web, dengan cakupan sekitar 82%. Selama dekade terakhir, banyak framework yang
mendukung PHP muncul. Framework seperti CodeIgniter, Symfony, Phalcon, dan Laravel banyak digunakan
dan sesuai dengan informasi dari sitepoint.com (Rajendra Chavan & Pawar, 2021). Laravel merupakan suatu
platform pengembangan web berbasis PHP yang bersifat open source. Framework ini telah diumumkan ke
publik dengan lisensi MIT, yang memungkinkan pengguna untuk mengakses sumber kode melalui Github.
Dikembangkan oleh Taylor Otwell, Laravel dirancang untuk memfasilitasi pembuatan aplikasi web dengan
mengikuti pola arsitektur model-view-controller (MVC) (Endra et al., 2021). Laravel sangat berguna dalam
pengembangan web, dikarenakan Laravel sendiri menyediakan banyak sekali tools untuk mempermudah
developer dalam mengembangkan website dengan cepat dan efisien (Falahi, 2019).

Selain PHP yang memiliki framework Laravel, ada juga bahasa pemrograman yang bagus untuk
pengembangan web ialah Javascript. Javascript adalah bahasa skrip yang sangat dinamis, bahasa ini menjadi
salah satu bahasa yang penting di dalam pengembangan web, dikarenakan Javascript bisa digunakan untuk
client side maupun server side (Oliveira & Mattos, 2019). Menurut (Siahaan & Octarian Vianto, 2021)
Javascript merupakan bahasa yang sangat terkenal dan mempunyai aspek yang penting dalam
pengembangan. Javascript juga berjalan di sebuah platform Node.js untuk di sisi server side. Javascript
memiliki framework yaitu Express.js. Express.js sangat sering digunakan untuk pengembangan web,
terutama pada RESTful API. Express.js dinilai dapat mengembalikan respon dengan kecepatan yang lebih
baik dari beberapa bahasa pemrograman server-side yang umumnya digunakan untuk membangun RESTful
API (Tahyudin & Zidni Iman Sholihati, 2022). Express.js sendiri juga dikenal sebagai framework yang
terkenal dari platform Node.js yang telah diedit dan diterbitkan ulang berkali-kali. Berdasarkan Express,
dibangun berbagai API sederhana dan aplikasi besar, seringkali juga menjadi dasar untuk framework lain,
seperti Nest (Demashov & Gosudarev, 2020).

Dalam survei Stack Overflow 2021, Express.js menempati posisi ketiga dengan 23,82% dari 67.
responden, karena ini bisa disimpulkan bahwa Express.js masih layak digunakan dan masih banyak
digunakan untuk pengembangan web di sisi server side (Mulana et al., 2022). Sedangkan untuk Laravel
menempati posisi ke dua belas dengan 10,12% dari 67.593 responden. Walau cukup jauh dengan Express.js,


tidak berarti bahwa Laravel sudah tidak layak digunakan. Di Indonesia Laravel masih banyak sekali
digunakan dalam pengembangan website terutama yang ingin mengembangkan sebuah website dengan
pola arsitektur model-view-controller (MVC).

Penelitian sebelumnya yang dilakukan oleh Luky Mulana, dkk dengan judul “Analisis Perbandingan
Kinerja Framework Codeigniter Dengan Express.Js Pada Server RESTful Api” dengan hasil penelitian
menunjukkan bahwa Express.js memiliki waktu respon rata-rata sebesar 438.64 ms, lebih cepat
dibandingkan framework Codeigniter yang memiliki waktu respon rata-rata sebesar 551.72 ms, sedangkan
jika menggunakan CPU dan memori, framework Express.js memiliki respon rata-rata. waktu 551,72 ms.
rata-rata 438,64 ms. daripada kerangka Codeigniter. Oleh karena itu Express.js cocok untuk sistem yang
diakses oleh banyak pengguna dan terletak di server dengan spesifikasi tinggi. Sedangkan framework
Codeigniter cocok untuk aplikasi dengan akses pengguna lebih sedikit dan dapat ditempatkan pada server
dengan spesifikasi rendah (Mulana et al., 2022).

Penelitian selanjutnya yang dilakukan oleh Abi Amarulloh, dkk dengan judul “Analisis Perbandingan
Performa Web Service Rest Menggunakan Framework Laravel, Django, Dan Node Js Pada Aplikasi Berbasis
Website” dengan hasil penelitian didapatkan bahwa framework NodeJS (Express) mempunyai kecepatan
eksekusi yang paling cepat sedangkan framework Django mempunyai kecepatan pemrosesan dalam hal
jumlah request per detik dan HTML Transferred, peringkat ketiga adalah Laravel Framework dengan rata-
rata keberhasilan sebesar 60 permintaan pada 3.000, 5.000, dan 7.000 data (Amarulloh et al., 2023).

Penelitian selanjutnya dilakukan oleh Nasrul, dkk yang berjudul “Pengembangan Rest Api Dengan
Menggunakan Express Js Untuk Mencari Mentor Pribadi” dengan hasil penelitian merancang dan
membangun Rest API yang memungkinkan siswa menemukan mentor pribadi yang sesuai dengan
kebutuhan mereka kode program studi. Penggunaan metodologi Express Js dan Scrum dalam
pengembangan aplikasi ini diharapkan dapat memudahkan terciptanya aplikasi native coding yang efisien
dan efektif (Performa et al., 2020).

Penelitian selanjutnya dilakukan oleh Wiku Galindra Wardhana, dkk yang berjudul “Implementasi
Teknologi Restful Web Service Dalam Pengembangan Sistem Informasi Perekaman Prestasi Mahasiswa
Berbasis Website” dengan hasil penelitian Buat situs web menggunakan kerangka Laravel dan
Implementasikan REST API menggunakan kerangka kerja Lumen. Sistem kemudian diuji menggunakan
black box pengujian dengan tingkat hasil pengujian 100%, menunjukkan bahwa sistem memenuhi
spesifikasi. Selain itu dilakukan pengujian usability dengan menggunakan skala usability sistem dengan nilai
sebesar 78. Berdasarkan nilai yang diperoleh maka sistem masuk dalam kategori baik dengan nilai skala C
dan rentang akseptabilitas berada dalam kategori dapat diterima (Galindra Wardhana et al., 2020).

Penelitian selanjutnya dilakukan oleh Oky Dwi Arianto, dkk dengan judul “Penerapan Restful Web
Service Dengan Framework Laravel Untuk Pembangunan Sistem Informasi Manajemen Sumber Daya
Manusia” dengan hasil penelitian Menerapkan layanan web RESTful dalam sistem informasi manajemen
sumber daya manusia. Layanan web RESTful adalah teknologi yang memungkinkan digunakan untuk
melakukan integrasi data. Layanan web RESTful dibangun menggunakan arsitektur REST menggunakan
framework Laravel. Arsitektur REST penelitian ini mendukung pertukaran informasi yang aman
berkolaborasi di perpustakaan paspor Laravel. Hasilnya, berbagai fungsi pemrosesan data sumber daya
manusia muncul sebagai layanan web RESTful dengan nilai kembalian dalam format JSON. JSON diterapkan
pada sistem informasi manajemen sumber daya manusia berdasarkan framework Laravel dapat digunakan
oleh berbagai jenis pengguna (Arianto & Susetyo, 2022)

Berdasarkan pembahasan di atas, pemilihan teknologi atau framework pada pengembangan aplikasi
sangat penting terutama pada pengembangan RESTful API, pemilihan framework sangat mempengaruhi
kinerja sebuah aplikasi. Oleh karena itu, penelitian ini bertujuan untuk mengetahui cara membandingkan
performa framework Laravel dan Express.js dan menjelaskan hasil analisis perbandingan performa
framework Laravel dan Express.js melalui pengujian performa.

Data penelitian yang akan digunakan adalah perkumpulan data identitas mahasiswa yang akan di
inject secara manual dan disimpan di MySql. Tujuan data tersebut ialah untuk melakukan pengujian
database pada server. Data tersebut nantinya akan dipanggil melalui framework Laravel dan Express.js dan
pada saat framework tersebut memberikan response maka akan dilakukan pengukuran untuk mengetahui
seberapa baik, bagus, dan cepat kinerja dari kedua framework tersebut.


#### II. STUDI PUSTAKA

#### A. API

API adalah antarmuka yang dibuat oleh pengembang sistem untuk dapat diakses secara terprogram.
API juga sering dianggap sebagai kumpulan teknik untuk menciptakan komunikasi antara komponen
perangkat lunak yang berbeda (Akmal & M Noviarsyah Dasaprawira, 2020). Tujuan penggunaan API adalah
untuk berbagi data antar aplikasi yang berbeda. Tujuan lain dari penggunaan API adalah untuk
mempercepat proses pengembangan aplikasi dengan menyediakan fungsionalitas tersendiri sehingga
pengembang tidak perlu lagi mendesain fungsionalitas serupa. API yang beroperasi pada tingkat sistem
operasi membantu aplikasi berkomunikasi dengan lapisan dasar dan satu sama lain sesuai dengan
serangkaian protokol dan spesifikasi khusus (Hasanuddin et al., 2022).

#### B. REST

Representational State Transfer (REST) adalah suatu kerangka kerja perangkat lunak yang
memperkenalkan prinsip-prinsip operasi API. Awalnya, REST dirancang sebagai pedoman untuk mengatur
pertukaran informasi dalam jaringan yang kompleks seperti Internet. Dengan arsitektur berbasis REST,
Anda dapat memastikan komunikasi yang efisien, cepat, dan handal dalam skala besar. Keunggulan REST
terletak pada kemampuannya untuk mendukung kinerja tinggi, serta fleksibilitas dalam implementasi dan
modifikasi. Ini membawa portabilitas dan transparansi lintas platform ke semua sistem API (Amarulloh et
al., 2023).

#### C. Laravel

Laravel merupakan framework berbasis PHP yang dapat digunakan untuk membantu optimasi
pengembangan website. Dengan mengunakan Laravel, website dapat bekerja lebih dinamis (Amarulloh et
al., 2023). Menurut Ramos Somya, dkk Framework Laravel mudah dipahami dan memfasilitasi otentikasi,
routing, session manager, cache dan beberapa kegunaan komponen lainnya di Laravel. Laravel sendiri
mempunyai software arsiktetur sendiri yaitu MVC (Kausar Bagwan & Swati Ghule, 2019). Dalam konsep
MVC (Model-View-Controller), desain sistem informasi dibagi menjadi tiga lapisan, yaitu model, tampilan,
dan pengendali. Model digunakan untuk mengelola informasi dan memberi tahu pengamat ketika informasi
berubah. Hanya model yang berisi data dan fungsi terkait pemrosesan data. Tampilan bertanggung jawab
untuk memetakan grafik ke suatu perangkat (Subari et al., 2021). Laravel juga menawarkan fitur seperti
migrasi database dan dukungan pengujian unit bawaan yang memudahkan pengembang untuk membangun
aplikasi yang kompleks (Somya & Nathanael, 2019).

#### D. ExpressJs

Menurut (Lisgiani & Nurmajid, 2022) Express.js adalah sebuah kerangka web yang ditulis dalam
bahasa pemograman Javascript. Dimana framework ini digunakan untuk membangun aplikasi dari backend
secara efisien dan optimal. Express menyajikan berbagai metode yang dapat digunakan untuk setiap jenis
permintaan HTTP seperti GET, POST, SET, dan lainnya. Selain itu, Express memungkinkan penggunaan
templat URL (rute) untuk menentukan jalur permintaan, mendefinisikan mesin templat (tampilan) yang
digunakan, dan metode yang akan menghasilkan respons. Framework ini juga dilengkapi dengan
middleware, yaitu perangkat lunak penengah, yang memungkinkan penambahan fungsi tambahan seperti
pengelolaan cookie, sesi, dan sebagainya (Achmad Ahlar Ridha et al., 2021).

#### III. METODE PENELITIAN

Metode pada penelitian ini menggunakan metode performance testing. Performance testing adalah
proses menjalankan suatu aplikasi dengan melakukan simulasi kepada pengguna virtual menggunakan
suatu alat seolah-olah aplikasi tersebut sedang berjalan dan diakses oleh pengguna sebenarnya untuk
melihat apakah sistem berfungsi dengan baik dan mempunyai kinerja yang baik (Mulana et al., 2022). Tujuan
dari melakukan performance testing adalah untuk menguji scalability, availability dan kinerja baik pada API.
Tahapan yang dilakukan pada penelitian ini dapat dilihat pada Gambar 1.


```
Gambar 1. Rancangan Penelitian
(Mulana et al., 2022)
```
#### A. Identify Test Environment

Sebelum menguji API, perlu menetapkan lingkungan pengujian yang akan memantau serta menguji
setiap permintaan yang akan disampaikan ke endpoint di berbagai framework. Dalam penelitian ini, kami
akan menggunakan dua mesin virtual dari layanan cloud DigitalOcean sebagai server. Setiap framework
akan dihosting di dua server terpisah, tetapi dengan spesifikasi yang identik. Hal ini bertujuan agar setiap
framework, baik Laravel maupun Express.js, dapat menangani permintaan tanpa saling berbagi sumber
daya di antara keduanya.

Alat yang akan digunakan untuk memantau hasil setiap permintaan pengujian adalah JMeter. JMeter
sendiri adalah alat pengujian kinerja berbasis desktop dari Apache, ditulis dalam bahasa pemrograman Java,
untuk menguji beban halaman web, aplikasi web, dan sumber statis atau dinamis lainnya, termasuk
database, file, Servlets, skrip Perl, Objek Java, Server FTP, dan lain-lain (Husufa & Prihandi, 2022). Dari
JMeter, akan dihasilkan laporan mengenai waktu respons dan tingkat kesalahan pada setiap endpoint. Selain
itu, untuk memantau sumber daya yang digunakan pada server, penulis akan menggunakan plugin data yang
disediakan oleh JMeter. Dengan ini, kami dapat mengawasi penggunaan CPU dan memori saat server
memproses setiap permintaan.

#### B.^ Identify Performance Acceptance Criteria

Dalam penelitian ini, data mahasiswa sebanyak 1000 baris akan digunakan. Hasil penelitian
menunjukkan bahwa sistem harus dapat memproses data dengan cepat. Berdasarkan kebutuhan sistem
yang tercantum dalam Tabel 2, salah satu fokus kinerja penelitian ini adalah waktu respons. Oleh karena itu,
untuk pengujian, penulis menetapkan batasan waktu respons maksimal tidak boleh melebihi 5 detik ketika
seribu pengguna mengakses kedua kerangka kerja tersebut. Selain waktu respons, karena penelitian ini
membandingkan dua kerangka kerja, penelitian juga akan memantau penggunaan sumber daya masing-
masing framework untuk tujuan kinerja lainnya, khususnya penggunaan CPU dan memori. Kriteria yang
ditegakkan dalam penelitian ini adalah bahwa penggunaan sumber daya oleh kedua framework harus tetap
di bawah 75% saat memproses beberapa permintaan secara bersamaan.

#### C. Plan And Design Test

Pada pengujian kedua framework, pengguna akan melakukan permintaan ke endpoint yang
menampilkan data mahasiswa. Pengujian direncanakan akan dilakukan dengan mengirimkan permintaan
dari klien menggunakan alat JMeter ke endpoint yang disediakan oleh kedua framework tersebut,
menggunakan metode HTTP GET.

#### D. Configure Test Environment

Pada tahap ini kita akan melakukan konfigurasi pada environment yang sudah disediakan dan
diidentifikasikan pada tahap diatas. Konfigurasi ini dilakukan agar penulis dapat melakukan pengujian,
konfigurasi meliputi setup server serta instalasi tools JMeter.


#### E. Implement Test Design

Pada fase ini, lingkungan telah disiapkan sesuai dengan skenario pengujian yang telah ditetapkan
pada langkah sebelumnya. JMeter, yang telah diinstal pada komputer klien, akan diatur konfigurasinya
sesuai dengan rencana pengujian yang telah disiapkan. Pembuatan rencana uji di JMeter akan seragam untuk
kedua framework, satu-satunya perbedaan adalah alamat IP publik masing-masing server. Rencana uji di
JMeter dirancang mengacu pada endpoint yang akan menangani setiap permintaan, yaitu
/api/mahasiswa/1000 dengan metode HTTP GET.

#### F. Execute Tests

Pada langkah ini, uji coba dilakukan dan hasilnya dipantau untuk kedua framework, yaitu Codeigniter
dan Express.js, guna memperoleh data yang akan digunakan pada tahap selanjutnya. Untuk menjalankan
rencana pengujian yang telah disimpan, Anda perlu menulis skrip pada prompt perintah. Pelaksanaan
rencana pengujian dipecah menjadi tiga perintah: -n -t [file_rencana_pengujian] untuk menjalankan file
pengujian di direktori yang telah ditentukan, -l [file_hasil] untuk membuat file csv yang memuat hasil
pengujian, dan -e -o [folder_laporan] untuk menyimpan hasil pengujian guna mempermudah proses analisis.

#### G.^ Analyze, Report and Retest

Pada tahap ini dilakukan analisis dan perbandingan terhadap kedua framework yang diuji dengan
tujuan untuk mengetahui performa masing-masing framework sehingga diperoleh hasil yang menunjukkan
framework mana yang paling optimal dari segi performa.

#### IV. HASIL DAN PEMBAHASAN

#### A. Identify Test Environment

Sebelum melakukan performance testing diperlukan dahulu untuk menyiapkan server dan alat,
penggunaan framework yang akan digunakan adalah framework Laravel versi 10.25.2 dan framework
Express.js yang akan dijalankan pada NodeJs versi 18.12.0, kemudian untuk spesifikasi server dan aplikasi
yang terinstal akan digunakan dapat dilihat pada Tabel 1.

**Tabel 1.** Spesifikasi Server
Server
Cloud Service VPS Digitalocean
CPU 1CPU
SSD 25GB
Web Server Nginx
Database
DBMS Mysql
Versi 8.0.
Tools
Aplikasi Apache JMeter
Versi 5.6.
Framework
Framework 1 Express.js dengan NodeJs
Versi 18.12.
Framework 2 Laravel Versi 10.25.
Pastikan server dan aplikasi sesuai dengan spesifikasi di atas sebelum melanjutkan dengan pengujian
performa. Dengan persiapan yang tepat, pengujian performa dapat dilakukan dengan akurat dan hasilnya
dapat diandalkan.

#### B. Identify Performance Acceptance Criteria

Selanjutnya langkah ini juga penting dalam pengujian performa perangkat lunak. Hal ini melibatkan
penetapan kriteria yang harus dipenuhi oleh sistem atau aplikasi selama pengujian kinerja agar dianggap
dapat diterima. Kriteria ini membantu menentukan apakah sistem memenuhi standar kinerja yang
diharapkan oleh pengguna. Untuk kriteria pengujian bisa dilihat pada Tabel 2.


```
Tabel 2. Kriteria Pengujian
Tujuan Kinerja Kriteria
Waktu Respon Kurang dari 5 detik Ketika
1000 pengguna akses secara
bersamaan
Penggunaan CPU Kurang dari 75%
Penggunaan
Memori
```
```
Kurang dari 75%
```
#### C. Plan And Design Test

Langkah selanjutnya juga tidak kalah penting dalam proses pengujian perangkat lunak. Pada tahap
ini, rencana pengujian dirinci dan desain pengujian dibuat untuk memastikan bahwa pengujian dilakukan
secara terstruktur dan efektif. Disini kita akan melakukan pengujian ke sebuah endpoint yang sudah
disediakan dan melakukan pengujian _request_ API secara bersamaan dari 100 user hingga dengan 1000 user.

Penting untuk dicatat bahwa dataset yang digunakan untuk pengujian ini berasal dari penulis sendiri,
di mana penulis secara manual melakukan penambahan data ke dalam basis data dengan 100 sampai dengan
1000 data mahasiswa. Tindakan manual ini melibatkan pengisian data ke dalam database untuk
mensimulasikan skenario penggunaan sehari-hari. Oleh karena itu, dataset ini mencerminkan situasi nyata
dan dihasilkan oleh tangan penulis sendiri.

Dengan menggunakan dataset yang telah penulis siapkan, pengujian dapat dilakukan pada endpoint
yang ditentukan, menggambarkan bagaimana sistem merespons ketika diakses secara bersamaan oleh
jumlah pengguna yang bervariasi. Hal ini memberikan pemahaman yang lebih mendalam tentang kinerja
sistem dalam menghadapi beban pengguna yang tinggi, sekaligus memvalidasi efektivitas rencana pengujian
yang telah disusun.

```
Gambar 2. Alur Pengujian
(Mulana et al., 2022)
```
#### D.^ Configure Test Environment

Pada tahap ini kita akan melakukan konfigurasi environment terlebih dahulu sebelum sehingga dapat
melakukan testing dengan environment yang sudah disediakan.

#### E. Implement Test Design

Langkah selanjutnya adalah kita akan melakukan testing dari 100 user hingga 1000 user ke endpoint
/get/mahasiswa/1000 secara satu per satu dengan menggunakan metode HTTP GET dan menggunakan alat
JMeter untuk testingnya.

#### F. Execute Tests

Selanjutnya kita akan mengeksekusi test yang telah dijelaskan diatas dengan menggunakan perintah
jmeter -n -t [test_plan_file], jmeter -l [file_result], jmeter -e -o [report_folder].


#### G. Analyze, Report and Retest

Hasil penelitian setelah menyelesaikan semua rencana pengujian akan menghasilkan hasil untuk
setiap kerangka. Hasil pengujian framework Laravel dapat dilihat pada Tabel 3.
**Tabel 3**. Hasil Pengujian framework Laravel
User Waktu Respon (ms) Penggunaan CPU Penggunaan Memori
100 1129 1 2,0% 4,3%
200 1506 1 2,6% 4,1%
300 1840 23,2% 4,1%
400 1475 19,2% 4 ,1%
500 1642 22,0% 4,2%
600 1884 1 9,7% 4,3%
700 1980 21,7% 4,3%
800 2175 20,7% 4,1%
900 1864 23,2% 4,3%
1000 1962 27,1% 4,3%
**Rata-rata 1 745,7 20,14% 4,21%**

Kemudian, hasil pengujian dari framework Express.js terdapat dalam Tabel 4, dengan waktu respons
rata-rata sekitar 19873,2 milidetik (ms). Sementara itu, rata-rata penggunaan CPU sebesar 50,64% dan
penggunaan memori sekitar 8,51%.
**Tabel 4.** Hasil Pengujian framework Express.js
User Waktu Respon (ms) Penggunaan CPU Penggunaan Memori
100 2174 11 ,6% 7,4%
200 5086 25,6% 6,4%
300 8206 32 , 6 % 8,5%
400 13634 37,2% 8,8%
500 17961 30 , 9 % 9,0%
600 11574 22,3% 9,0%
700 14761 2 6,6% 9,1%
800 11521 3 2,4% 9,1%
900 13465 3 2,1% 8,8%
1000 10169 33 ,1% 9,0%
**Rata-rata 10855 , 1 28 , 44 % 8,51%**

```
Dari hasil pengujian yang telah dilakukan, diperoleh rata-rata waktu respons, penggunaan CPU, dan
penggunaan memori untuk kedua framework, yaitu Laravel dan Express.js. Peneliti akan menganalisis hasil
pengujian tersebut dengan merinci data dalam bentuk grafik-gafik.
Hasil pengujian yang pertama kali adalah mengukur waktu respon dari setiap framework yang
dipakai, terlihat dari Tabel 3 Laravel memiliki waktu respon rata-rata 1745,7 ms dibanding Express.js
dengan waktu respon rata-rata 10855,1 jika diakses secara bersamaan. Terlihat juga bahwa Express.js
kurang stabil jika diakses secara bersamaan. Sehingga membuat Laravel lebih unggul di waktu respon.
Dalam rangka mengevaluasi performa framework Laravel dan Express.js, uji kinerja dilakukan
dengan fokus pada pengukuran penggunaan CPU dan memori dari masing-masing framework yang
digunakan. Analisis data yang terdapat dalam Tabel 3 dan Tabel 4 menyajikan perbandingan kinerja antara
kedua framework tersebut. Laravel menunjukkan hasil yang lebih baik dengan penggunaan CPU rata-rata
sekitar 20,14% dan penggunaan memori sekitar 8,52%, mengatasi Express.js ketika diakses bersamaan.
Grafik juga menunjukkan bahwa Express.js menyerap lebih banyak sumber daya CPU dan memori
dibandingkan Laravel dalam situasi akses bersamaan.
Penggunaan CPU Laravel yang terbilang rendah, sekitar 20,14%, menunjukkan efisiensi dalam
melaksanakan operasi, menciptakan beban kerja yang lebih ringan pada CPU. Sebaliknya, Express.js
menunjukkan penggunaan CPU yang lebih tinggi, mungkin mengindikasikan beban kerja yang lebih berat
pada server saat diakses bersamaan. Penggunaan memori Laravel sekitar 8,52% menandakan
```

kemampuannya mengelola sumber daya memori dengan efisien, sementara Express.js menampilkan
penggunaan memori yang lebih tinggi, menandakan kebutuhan sumber daya memori yang lebih besar ketika
menghadapi akses pengguna secara bersamaan.

Oleh karena itu, kesimpulan yang dapat ditarik dari uji kinerja ini adalah bahwa Laravel memiliki
keunggulan dalam menangani akses pengguna secara bersamaan dengan efisiensi tinggi. Penggunaan CPU
dan memori yang lebih rendah menjadikan Laravel pilihan yang lebih superior dalam konteks aplikasi
dengan beban kerja tinggi atau ketika banyak pengguna mengakses sistem secara bersamaan.

Dari semua pengujian yang sudah dilakukan, diperoleh rata-rata waktu respon, penggunaan CPU dan
memori. Dari hasil tersebut terlihat bahwa framework Laravel bekerja lebih bagus dan lebih cepat
dibandingkan dengan framework Express.js, Sehingga membuat framework Laravel ini lebih cocok
diimplementasikan kepada sistem yang banyak diakses oleh user secara bersamaan langsung. Sedangkan
framework Express.js dari hasil pengujian terlihat lebih lambat, namun Express.js masih cocok untuk
diimplementasikan ke sistem yang tidak banyak yang diakses oleh user secara bersamaan.

#### V. SIMPULAN

Tujuan penelitian pengujian kinerja ini adalah untuk membandingkan efisiensi dan kinerja antara dua
framework utama, yaitu Laravel dan Express.js, khususnya dalam pembangunan RESTful API. Fokus
pengujian ditempatkan pada respons API terhadap akses data mahasiswa dalam basis data MySQL. Rencana
pengujian ini mencakup skenario pengujian yang realistis dan metrik kinerja, seperti waktu respons.

Dari hasil pengujian, kesimpulan dapat ditarik bahwa Laravel memiliki kinerja yang lebih unggul,
dengan waktu respon rata-rata 1745,7 ms lebih cepat dibandingkan dengan Express.js. Hal ini menandakan
bahwa Laravel lebih sesuai untuk aplikasi dengan tingkat akses pengguna yang tinggi secara bersamaan. Di
sisi lain, Express.js memberikan waktu respon rata-rata sekitar 10855,1 ms, membuatnya menjadi opsi yang
baik untuk aplikasi dengan jumlah pengguna simultan yang lebih rendah.

Penting untuk mencatat bahwa hasil pengujian kinerja ini juga sangat dipengaruhi oleh spesifikasi
server yang digunakan. Oleh karena itu, pemilihan framework harus mempertimbangkan baik kecepatan
respons maupun spesifikasi server yang relevan dengan kebutuhan aplikasi. Dengan demikian, penelitian
ini memberikan panduan yang lebih jelas bagi pengembang dalam memilih framework yang sesuai dengan
karakteristik proyek dan memenuhi standar kinerja yang diinginkan.

#### DAFTAR PUSTAKA

Achmad Ahlar Ridha, Hamidillah Ajie, & M. Ficky Duskarnaen. (2021). Pengembangan Web Service Sistem
Pembayaran Multibank Universitas Negeri Jakarta. PINTER : Jurnal Pendidikan Teknik Informatika
Dan Komputer, 5(1), 25–33. https://doi.org/10.21009/pinter.5.1.
Akmal, N. K., & M Noviarsyah Dasaprawira. (2020). RANCANG BANGUN APPLICATION PROGRAMMING
INTERFACE (API) MENGGUNAKAN GAYA ARSITEKTUR GRAPHQL UNTUK PEMBUATAN SISTEM
INFORMASI PENDATAAN ANGGOTA UNIT KEGIATAN MAHASISWA (UKM) STUDI KASUS UKM
STARLABS. 3–10.
Amarulloh, A., Kurniasih, & Muchlis. (2023). Analisis Perbandingan Performa Web Service Rest
Menggunakan Framework Laravel, Django, dan Node JS pada Aplikasi Berbasis Website. Jurnal Teknik
Informatika Stmik Antar Bangsa, 9(1), 12–17.
Arianto, O. D., & Susetyo, Y. A. (2022). Penerapan Restful Web Service Dengan Framework Laravel Untuk
Pembangunan Sistem Informasi Manajemen Sumber Daya Manusia. JIPI (Jurnal Ilmiah Penelitian Dan
Pembelajaran Informatika), 7(2), 522– 5 32. https://doi.org/10.29100/jipi.v7i2.
Demashov, D., & Gosudarev, I. (2020). Efficiency evaluation of Node.js web-server frameworks. CEUR
Workshop Proceedings, 2590, 1–8.
Endra, R. Y., Aprilinda, Y., Dharmawan, Y. Y., & Ramadhan, W. (2021). Analisis Perbandingan Bahasa
Pemrograman PHP Laravel dengan PHP Native pada Pengembangan Website. EXPERT: Jurnal
Manajemen Sistem Informasi Dan Teknologi, 11(1), 48. https://doi.org/10.36448/expert.v11i1.
Falahi, M. T. (2019). Rancang Bangun Aplikasi Paperless Office Berbasis WEB Sebagai Sistem Pengolahan
dan Pencatatan Data Menggunakan RESTFUL API. Jurnal Manajemen Informatika (JAMIKA), 9(02),
153 – 161.


Fani, M., Nelmiawati, N., & Thohari, A. H. (2021). Sistem Pendataan Barang Milik Negara dengan Secured QR
Code dan REST API. Journal of Applied Informatics and Computing, 5(1), 43–48.
https://doi.org/10.30871/jaic.v5i1.
Galindra Wardhana, W., Arwani, I., & Rahayudi, B. (2020). Implementasi Teknologi Restful Web Service
Dalam Pengembangan Sistem Informasi Perekaman Prestasi Mahasiswa Berbasis Website (Studi
Kasus: Fakultas Teknologi Pertanian Universitas Brawijaya). Jurnal Pengembangan Teknologi
Informasi Dan Ilmu Komputer, 4(2), 680–689. [http://j-ptiik.ub.ac.id](http://j-ptiik.ub.ac.id)
Hasanuddin, Asgar, H., & Hartono, B. (2022). Rancang Bangun Rest Api Aplikasi Weshare Sebagai Upaya
Mempermudah Pelayanan Donasi Kemanusiaan. Jurnal Informatika Teknologi Dan Sains, 4(1), 8–14.
https://doi.org/10.51401/jinteks.v4i1.
Husufa, N., & Prihandi, I. (2022). Optimizing JMeter on Performance Testing Using the Bulk Data Method. Journal
of Information Systems and Informatics, 4(2), 205–215. https://doi.org/10.51519/journalisi.v4i2.
Kausar Bagwan, M. I., & Swati Ghule, P. D. (2019). A Modern Review on Laravel-PHP Framework. IRE Journals,
2(12), 1 – 3.
Lisgiani, R., & Nurmajid, S. (2022). Implementasi Autentikasi Dari Sisi Backend Pada Arsitektur Microservices
Menggunakan Express Js. Infotronik : Jurnal Teknologi Informasi Dan Elektronika, 7(1), 27.
https://doi.org/10.32897/infotronik.2022.7.1.
Mulana, L., Prihandani, K., Rizal, A., Singaperbanga, U., & Abstract, K. (2022). Analisis Perbandingan Kinerja
Framework Codeigniter Dengan Express.Js Pada Server RESTful Api. Jurnal Ilmiah Wahana Pendidikan,
8(16), 316–326. https://doi.org/10.5281/zenodo. 7067707
Oliveira, F. L., & Mattos, J. C. B. (2019). State-of-the-Art Javascript Language for Internet of Things. 149–154.
https://doi.org/10.5753/sbesc_estendido.2019.
Performa, U. J. I., Perbandingan, D. A. N., & Mysql, R. (2020). Jurnal Informatika Terpadu HIVE-HADOOP. 6(1), 20–
28.
Rajendra Chavan, P., & Pawar, S. (2021). Comparison Study Between Performance of Laravel and Other PHP
Frameworks. International Journal of Research in Engineering, Science and Management , 4(10), 27–29.
Siahaan, M., & Octarian Vianto, V. (2021). Comparative Analysis Study of Front-End JavaScript Frameworks
Performance Using Lighthouse Tool. Jurnal Mantik, 6(3), 2685–4236.
Somya, R., & Nathanael, T. M. E. (2019). Pengembangan Sistem Informasi Pelatihan Berbasis Web Menggunakan
Teknologi Web Service Dan Framework Laravel. Jurnal Techno Nusa Mandiri, 16(1), 51–58.
https://doi.org/10.33480/techno.v16i1.
Subari, A., Manan, S., & Ariyanto, E. (2021). Implementation of MVC (Model-View-Controller) architecture in
online submission and reporting process at official travel warrant information system based on web
application. Journal of Physics: Conference Series, 1918(4), 0–7. https://doi.org/10.1088/1742-
6596/1918/4/
Syafrial, M., & Teguh, I. (2019). Penerapan Metode Representational State Transfer (Restfull) Web Services Pada
Pembuatan KTP Dan Kartu Keluarga. Teknois : Jurnal Ilmiah Teknologi Informasi Dan Sains, 7(2), 37–46.
https://doi.org/10.36350/jbs.v7i2.
Tahyudin, I., & Zidni Iman Sholihati. (2022). Pengembangan Aplikasi Tiga-Tingkat Menggunakan Metode Scrum
pada Aplikasi Presensi Karyawan Glints Academy. Jurnal RESTI (Rekayasa Sistem Dan Teknologi
Informasi), 6(1), 169–176. https://doi.org/10.29207/resti.v6i1.
Utomo, S. P., Alfiyah, N. H., Sani, Z. A., Hanafi, M., & Primadewi, A. (2020). Informasi Terintegrasi Menggunakan
FrameWork CodeIgniter. Seminar Nasional Dinamika Informatika, 124–128.
Verma, D. (2022). A comparison of web framework efficiency: performance and network analysis of modern web
frameworks. https://www.theseus.fi/bitstream/handle/10024/755038/Verma_Dhruv.pdf?sequence=
Viglianisi, E., & Dallago, M. (2020). R EST T EST G EN : Automated Black-Box Testing of RESTful APIs.


