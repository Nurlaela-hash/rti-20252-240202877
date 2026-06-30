# Rencana Penelitian: Perbandingan Performa Laravel vs Express.js pada REST API CRUD Kompleks

## 1. Ringkasan

| Item | Keterangan |
|---|---|
| Judul | Performance Evaluation of Laravel and Express.js backend frameworks for complex CRUD operations under high concurrency |
| Target Publikasi | Jurnal JUTIF / Jurnal RESTI (Sinta 2) |
| Stack | Docker Compose, MySQL 5.7, Node.js (Express.js), PHP (Laravel 11), k6 |
| Masalah | Pemilihan framework backend didominasi opini subjektif, bukan data empiris pada data berskala produksi. |
| Solusi | Eksperimen komparatif terkontrol pada 100K data relasional dengan limitasi CPU/Memory container yang identik. |

## 2. Alur Kerja (Roadmap)

Setiap tahap memiliki file rencana detail tersendiri agar lebih rapi:

- [x] **Tahap 1** — [Perancangan Arsitektur & Skema Database](tahap-1-arsitektur-dan-skema-database.md) — *Selesai*
- [x] **Tahap 2** — [Implementasi Service Express & Laravel](tahap-2-implementasi-service-express-dan-laravel.md) — *Selesai*
- [x] **Tahap 3** — [Skrip & Eksekusi Pengujian k6](tahap-3-pengujian-k6.md) — *Selesai*
- [x] **Tahap 4** — [Ekstraksi Data & Analisis Hasil](tahap-4-analisis-data.md) — *Selesai*
- [x] **Tahap 5** — [Draf Paper Jurnal & Hasil Evaluasi](tahap-5-draf-paper.md) — *Selesai*

---

## 3. Catatan

Dokumen ini adalah indeks utama perencanaan. Detail teknis, skema, dan keputusan masing-masing tahap dicatat pada file `tahap-N-*.md` terkait dan diperbarui seiring progres pengerjaan.
