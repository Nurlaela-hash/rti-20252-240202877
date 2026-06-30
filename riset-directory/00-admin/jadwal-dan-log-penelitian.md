# Jadwal & Log Pelaksanaan Penelitian

Catatan kronologis pelaksanaan tiap tahap (sumber: riwayat commit git & dokumen `09-docs/tahap-N-*.md`). 

## Log Pelaksanaan

| Tanggal | Tahap | Aktivitas | Referensi |
|---|---|---|---|
| Juni 2026 | Tahap 1 | Perancangan arsitektur basis data e-commerce relasional (5 tabel: categories, brands, products, inventory, reviews) serta penentuan spesifikasi OpenAPI `openapi.yaml`. | [09-docs/tahap-1-arsitektur-dan-skema-database.md](../09-docs/tahap-1-arsitektur-dan-skema-database.md) |
| Juni 2026 | Tahap 2 | Implementasi endpoint REST API ekuivalen pada Express.js (Node.js) dan Laravel (PHP). Pembuatan Dockerfile, file `.dockerignore`, dan penyelarasan variabel lingkungan database `.env`. | [09-docs/tahap-2-implementasi-service-express-dan-laravel.md](../09-docs/tahap-2-implementasi-service-express-dan-laravel.md) |
| 1 Juli 2026 | Tahap 3 | Integrasi Docker Compose terstandardisasi dengan parameter penormalan device (`platform: linux/amd64` dan *resource limits*). Eksekusi program seeding 100K data produk sintetik deterministik, serta pengeksekusian load test k6 sebanyak 10 run independen per framework via `run-experiments.ps1`. | [09-docs/tahap-3-pengujian-k6.md](../09-docs/tahap-3-pengujian-k6.md) |
| 1 Juli 2026 | Tahap 4 | Pembuatan utilitas pemroses log `parse_results.py` berbasis Python untuk mengekstrak dan menghitung deviasi standar serta rata-rata performa (*throughput* dan *latensi*) dari berkas log biner UTF-16 k6. | [09-docs/tahap-4-analisis-data.md](../09-docs/tahap-4-analisis-data.md) |
| 1 Juli 2026 | Tahap 5 | Penyusunan laporan akhir komprehensif pada dokumen `08-laporan/laporan-penelitian.md` serta penyelesaian lembar kerja `worksheets/ws-09-implementation.md`. | [09-docs/tahap-5-draf-paper.md](../09-docs/tahap-5-draf-paper.md), [08-laporan/laporan-penelitian.md](../08-laporan/laporan-penelitian.md) |

## Status Ringkas

* **Tahap 1–4**: Selesai (ekstraksi data final dari 10 run load testing per framework telah dilakukan pada 1 Juli 2026).
* **Tahap 5**: Laporan akhir diselesaikan dengan melampirkan hasil analisis data performa secara statistik deskriptif.

## Item Tindak Lanjut (Checklist Akhir)

- [x] Menyusun Dockerfile dan docker-compose.yml yang *portable* lintas mesin.
- [x] Mengunci versi dependensi npm dan composer (*freeze dependencies*).
- [x] Menjalankan seeding 100.000 data relasional secara deterministik.
- [x] Melaksanakan pengujian load-testing sebanyak 10x per framework.
- [x] Mengekstrak rata-rata dan standar deviasi latensi (avg, med, p95) dan throughput (RPS).
- [x] Menyusun laporan akhir penelitian yang reproduksibel.
