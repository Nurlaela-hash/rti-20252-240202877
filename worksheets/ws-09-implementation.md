# WS-09: Implementation & Environment

> **Bab 9 — Implementasi Riset & Kontrol Lingkungan**

---

## Ringkasan Materi

### Implementasi Riset ≠ Coding Biasa

Tujuan implementasi riset bukan membuat software yang berfungsi, melainkan membangun **instrumen pengukuran yang konsisten**. Setiap modul harus di-mapping ke variabel (dari Bab 6), parameter harus config-driven, dan logging aktif dari hari pertama.

> **Mengapa reproducibility penting?** Sains dibangun di atas prinsip verifikasi — temuan harus bisa dikonfirmasi oleh peneliti lain. _Replicability crisis_ yang terjadi di banyak paper riset ML/AI disebabkan oleh environment tidak terdokumentasi: orang lain tidak bisa reproduksi, hasil diragukan, kepercayaan terhadap temuan hilang. Prinsip: **dokumentasi environment = snapshot kredibilitas riset Anda.**

### Reproducible Implementation Model

```
Design → Implementation → Environment Setup → Execution Consistency → Reproducibility → Trustworthy Result
```

Setiap transisi memiliki syarat:
- Design → Implementation: kode sesuai mapping variabel-ke-komponen
- Implementation → Environment: versi, dependency, seed, path, OS eksplisit
- Environment → Consistency: seed terkunci, urutan deterministik
- Consistency → Reproducibility: dokumentasi lengkap
- Reproducibility → Trust: siapa pun ikuti dokumentasi → hasil sama/serupa

### Repeatability vs Reproducibility

| Level | Peneliti | Environment | Hasil |
|-------|---------|-------------|-------|
| **Repeatability** | Sama | Sama | Sama persis |
| **Reproducibility** | Berbeda | Berbeda (ikuti docs) | Sama/serupa |

Capai **repeatability** dulu, baru **reproducibility**.

### Engineering vs Research Perspective

| Aspek | Engineering | Research |
|-------|-----------|---------|
| Tujuan | Sistem berfungsi untuk user | Instrumen pengukuran konsisten |
| Dependency | Update ke terbaru | Lock di versi spesifik |
| Testing | Unit, integration, E2E | Repeatability test (run ulang → sama?) |
| Dokumentasi | User guide, API docs | Environment spec, execution steps, expected output |
| Config | Default masuk akal | Setiap parameter eksplisit & adjustable |

### Jebakan Kognitif

1. Menunda environment setup → bug sulit dilacak
2. Tidak pakai version control → hasil tidak bisa direkonstruksi
3. Menolak Docker/container → "di laptop saya bisa" saat review
   - **Docker** = teknologi container yang "membungkus" aplikasi beserta seluruh dependency-nya dalam satu unit terisolasi. Hasilnya: kode berjalan identik di laptop, server, maupun reviewer lain. Intro singkat: `docker run -v $(pwd):/workspace environment-image python run_experiment.py`
4. 3× hasil sama ≠ repeatable (bisa cache/state tersimpan)

### Dependency Locking

Mengandalkan "install library terbaru" berbahaya: versi berbeda = perilaku berbeda = hasil tidak reproducible. Praktik:
- **Python**: buat `requirements.txt` dengan versi eksplisit: `scikit-learn==1.3.2`, lalu kunci dengan `pip freeze > requirements.txt`
- **Conda**: gunakan `conda env export > environment.yml` untuk snapshot lengkap
- **Node.js/R/Julia**: gunakan `package-lock.json` / `renv.lock` / `Project.toml` — semua fungsi serupa: lock versi + hash

### Istilah Penting

- **Environment Specification** — Deskripsi lengkap: hardware, OS, runtime, library + versi, config, seed
- **Dependency** — Komponen eksternal yang harus di-lock versinya
- **Config-driven** — Parameter dieksternalisasi ke file konfigurasi, bukan hardcode

---

## Template A.9 — Dokumentasi Setup Eksperimen

```
EXPERIMENT SETUP DOCUMENTATION

Hardware:
  CPU     : Intel Core i5-12400F (6 Cores, 12 Threads)
  RAM     : 16 GB DDR4 Dual-Channel
  GPU     : NVIDIA GeForce RTX 3060 12GB GDDR6
  Storage : SSD NVMe PCIe Gen 4.0 512GB

Software:
  OS        : Windows 11 Home (Host) / Alpine Linux 3.19 (Docker Containers)
  Runtime   : Node.js 20.18-alpine / PHP 8.4-cli-alpine / MySQL 5.7
  Framework : Express.js 4.21.2 / Laravel 11.31.0

Dependencies:
| Library | Version | Sumber | Hash/Checksum |
|---------|---------|--------|---------------|
| mysql2 | 3.11.5 | npm | (dari package-lock.json) |
| dotenv | 16.4.7 | npm | (dari package-lock.json) |
| laravel/framework | 11.31.0 | composer | (dari composer.lock) |

Konfigurasi:
  Config file     : docker-compose.yml, service-laravel/.env, service-express/.env
  Random seed     : Seed = 42 (untuk generator data dummy database)
  Hyperparameters : cpus: '1.0' & memory: 512M (untuk application containers), cpus: '2.0' & memory: 1024M (untuk MySQL container)

Reproducibility Check:
  [x] Dependency terdokumentasi (requirements.txt / lock file)
  [x] Seed ditetapkan di semua level (Python, NumPy, framework)
  [x] Config di version control
  [x] README instruksi reproduksi lengkap
```

---

## Latihan 1 — Environment Specification

Dokumentasikan environment untuk eksperimen Anda (boleh environment saat ini atau yang direncanakan).

| Komponen | Spesifikasi |
|----------|------------|
| CPU | Intel Core i5-12400F (Host) / Docker cap: 1.0 CPU (backends), 2.0 CPU (MySQL) |
| RAM | 16 GB DDR4/DDR5 (Host) / Docker cap: 512MB (backends), 1024MB (MySQL) |
| GPU | NVIDIA GeForce RTX 3060 12GB |
| OS | Windows 11 / Docker (Alpine Linux) |
| Runtime | Node.js 20-alpine & PHP 8.4-cli-alpine (platform: linux/amd64) |
| Framework | Express.js 4.21.2 & Laravel 11.31+ |
| Random Seed | 42 |


**Dependencies (minimal 5):**

| Library | Version | Alasan Dibutuhkan |
|---------|---------|-------------------|
| *laravel/framework* | *^11.31* | *Framework backend Laravel untuk REST API CRUD* |
| *express* | *^4.21.2* | *Framework backend Express.js untuk REST API CRUD* |
| *mysql2* | *^3.11.4* | *Driver MySQL untuk Express dan script seed* |
| *dotenv* | *^16.4.5* | *Membaca konfigurasi environment (.env)* |
| *k6 (Docker)* | *0.54.0* | *Instansi load testing untuk performa benchmark* |


---

## Latihan 2 — Repeatability Test Plan

Rancang tes repeatability sederhana: jalankan kode yang sama 3× di environment yang sama.

| Run | Seed | Metrik Utama | Hasil Sama? |
|-----|------|-------------|-------------|
| 1 | 42 | Throughput (req/sec) | — |
| 2 | 42 | Throughput (req/sec) | [x] Ya / [ ] Tidak |
| 3 | 42 | Throughput (req/sec) | [x] Ya / [ ] Tidak |

**Jika hasil berbeda, kemungkinan penyebab:**

Thermal throttling pada CPU, pengaruh background process dari sistem operasi Windows host, serta ketidakstabilan IOPS disk database MySQL pada volume Docker.

**Checklist kontrol yang sudah diterapkan:**
- [x] Random seed di-set di semua level
- [x] Tidak ada background process yang mengganggu (antivirus dinonaktifkan sementara/host dalam keadaan idle)
- [x] Cache dibersihkan antar-run (menggunakan restart container database)
- [x] Config file yang sama untuk semua run

---

## Latihan 3 — README Eksperimen

Tulis README minimum untuk eksperimen Anda (6 komponen wajib).

```markdown
# Judul Eksperimen: Perbandingan Performa REST API CRUD Laravel vs Express.js

## 1. Environment
- CPU (Host): Intel Core i5-12400F
- CPU (Docker Cap): 1.0 core (Express/Laravel), 2.0 cores (MySQL)
- RAM (Host): 16 GB DDR4/DDR5
- RAM (Docker Cap): 512MB (Express/Laravel), 1024MB (MySQL)
- Platform: linux/amd64
- OS: Windows 11 / Docker (Alpine Linux)
- Runtime: Node.js 20-alpine & PHP 8.4-cli-alpine
- Framework: Express.js 4.21.2 & Laravel 11.31+
- Random Seed: 42

## 2. Installation
Build seluruh container dengan:
$ docker compose build

## 3. Data
Database MySQL 5.7 dengan 5 tabel relasional (categories, brands, products, inventory, reviews). Volume data produk sebesar 100K baris yang digenerate secara deterministik dengan seed 42.

## 4. Execution
1. Nyalakan backend & database:
   $ docker compose up -d
2. Seed data:
   $ docker compose run --rm db-seed
3. Jalankan load testing:
   Express:
   $ docker compose run --rm -e K6_BASE_URL=http://express:3000 -e TARGET_FRAMEWORK=express k6
   Laravel:
   $ docker compose run --rm -e K6_BASE_URL=http://laravel:8000 -e TARGET_FRAMEWORK=laravel k6

## 5. Configuration
Dikonfigurasi melalui file `.env` di root directory. Parameter utama meliputi:
- `TOTAL_PRODUCTS=100000`
- `SEED=42`
- `CONCURRENT_USERS=100`
- `SUSTAIN_SECONDS=120`

## 6. Expected Output
Log keluaran k6 yang menampilkan total HTTP requests, HTTP throughput (req/s), HTTP request duration (min, avg, med, p(95), p(99)), dan success rate (>95%).
```

---

## Refleksi

> Apakah eksperimen Anda saat ini bisa direproduksi oleh orang lain tanpa bantuan Anda? Komponen apa yang masih hilang?

**Level saat ini:** [ ] Repeatability / [x] Reproducibility / [ ] Belum keduanya
**Komponen yang belum terdokumentasi:**
Semua komponen penting, termasuk dependensi terkunci, skema database, seed generator, container environment, dan perintah eksekusi run k6 telah terdokumentasi sepenuhnya pada berkas README.md dan file ini.

