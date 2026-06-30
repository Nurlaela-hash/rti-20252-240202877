# Tahap 3 — Skrip Pengujian k6 (Legitimate vs Attack Traffic)

**Status:** Selesai — matrix 400 run (40 replikasi) sudah dijalankan. **Bergantung pada:** [tahap-2-implementasi-gateway.md](tahap-2-implementasi-gateway.md)
**Lokasi kode:** [../05-kode/k6](../05-kode/k6)

---

## Tujuan

Menyusun skenario k6 untuk membandingkan gateway pada mode `CACHE_MODE=none` (baseline) vs `CACHE_MODE=hybrid` (mitigasi), dengan tiga jenis traffic:

- **Legitimate traffic** — request dengan JWT valid (`kid` dikenal), mensimulasikan beban normal.
- **Attack traffic** — request dengan JWT ber-`kid` acak/tidak terdaftar, mensimulasikan JWKS Endpoint Flooding (CVE-2026-48524).
- **Mixed traffic** — legitimate + attack berjalan bersamaan, untuk mengukur dampak mitigasi terhadap pengalaman user legit saat diserang.

... (truncated) ...
