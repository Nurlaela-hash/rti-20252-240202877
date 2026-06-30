# Tahap 1 — Perancangan Arsitektur & Skema Database

**Status:** Selesai

---

## 1. Komponen Sistem

1. **Service Express.js (Node.js 20, raw MySQL driver)** — Menerima request CRUD produk e-commerce, mengelola transaksi database MySQL via *connection pooling*.
2. **Service Laravel 11 (PHP 8.4, Laravel Query Builder)** — Menerima request CRUD produk yang identik menggunakan Query Builder/Eloquent, melayani endpoint yang sama secara fungsional.
3. **MySQL 5.7 Database Container** — Menampung data relasional 5 tabel (categories, brands, products, inventory, reviews).
4. **k6 (Grafana k6)** — Menginjeksi beban uji performa (20 VU, total 70 detik) dengan skenario CRUD acak (68% listing, 18% show detail, 6% create, 5% update, 3% delete).

## 2. Topologi Jaringan & Isolasi Resource

Untuk menjamin keadilan (*fairness*) perbandingan performa, sumber daya CPU dan Memory kontainer dibatasi secara ketat dalam `docker-compose.yml`:

```
┌─────────────────────────────────────────────────────────────────┐
│                           DOCKER HOST                           │
│                                                                 │
│  ┌──────────────────┐  ┌──────────────────┐  ┌────────────────┐ │
│  │   express-app    │  │   laravel-app    │  │   mysql-db     │ │
│  │  (Node.js 20)    │  │   (PHP 8.4-cli)  │  │  (MySQL 5.7)   │ │
│  │  Cap: 1.0 CPU    │  │  Cap: 1.0 CPU    │  │  Cap: 2.0 CPU  │ │
│  │  Cap: 512MB RAM  │  │  Cap: 512MB RAM  │  │  Cap: 1GB RAM  │ │
│  └────────┬─────────┘  └────────┬─────────┘  └────────┬───────┘ │
│           │                     │                     │         │
│           └──────────────┬──────┴─────────────────────┘         │
│                          │ (Docker Network)                     │
│                  ┌───────┴──────┐                               │
│                  │      k6      │                               │
│                  │ (grafana/k6) │                               │
│                  └──────────────┘                               │
└─────────────────────────────────────────────────────────────────┘
```

## 3. Skema Database (MySQL)

Skema relasional 5 tabel dideklarasikan dalam `db/schema.sql`:

```sql
CREATE TABLE categories (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  slug VARCHAR(140) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_categories_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE brands (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  country VARCHAR(80) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_brands_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE products (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  category_id INT UNSIGNED NOT NULL,
  brand_id INT UNSIGNED NOT NULL,
  sku VARCHAR(40) NOT NULL,
  name VARCHAR(180) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(12,2) NOT NULL,
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (id),
  UNIQUE KEY uq_products_sku (sku)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE inventory (
  product_id BIGINT UNSIGNED NOT NULL,
  stock INT NOT NULL,
  reserved INT NOT NULL,
  warehouse_location VARCHAR(80) NOT NULL,
  PRIMARY KEY (product_id),
  CONSTRAINT fk_inventory_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE reviews (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  product_id BIGINT UNSIGNED NOT NULL,
  rating TINYINT UNSIGNED NOT NULL,
  title VARCHAR(160) NOT NULL,
  body TEXT NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_reviews_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## 4. Keputusan Teknis (Final)

1. **Platform Pinned (`platform: linux/amd64`)**: Menjamin semua device (termasuk M1/M2 Mac atau Windows x86) menjalankan instruksi runtime yang ekuivalen.
2. **Kredensial Database Terstandar**: User `benchmark`/`benchmark` digunakan secara konsisten baik untuk koneksi internal container maupun seeding host.
3. **Penyelarasan Driver Non-Database**: Laravel dikonfigurasi untuk mematikan database driver session (`SESSION_DRIVER: file`) dan cache (`CACHE_STORE: file`) agar database MySQL murni hanya melayani transaksi CRUD data bisnis, menjamin komparasi yang ekuivalen dengan Express.
