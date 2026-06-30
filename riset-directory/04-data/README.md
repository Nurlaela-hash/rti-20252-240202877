# Dataset eksperimen

Folder ini menyimpan dataset eksperimen (output seed) dan artefak data yang dipakai untuk pengujian performa.

Cara menghasilkan dataset (deterministik):

1. Buka folder prototype: [riset-directory/05-kode/prototype](riset-directory/05-kode/prototype)
2. Pastikan dependensi terpasang: jalankan `npm install` di folder prototype.
3. Jalankan skrip seed yang ada di `riset-directory/05-kode/prototype/scripts/seed-database.js`:

```powershell
cd riset-directory/05-kode/prototype
npm run seed
```

4. Salin file output (mis. `dataset-100k.csv` atau `dump.sql`) ke folder ini.

Catatan: versi awal template dipindahkan ke `riset-directory/archived/04-data/` jika diperlukan sebagai referensi.
