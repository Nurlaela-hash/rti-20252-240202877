<?php

namespace App\Http\Controllers\Api;

use App\Support\Database;
use App\Support\Http;
use PDO;

final class ProductController
{
    private static function buildFilter(array $query): array
    {
        $filters = [];
        $values = [];

        if (!empty($query['search'])) {
            $filters[] = '(p.name LIKE ? OR p.sku LIKE ? OR p.description LIKE ?)';
            $search = '%' . $query['search'] . '%';
            $values[] = $search;
            $values[] = $search;
            $values[] = $search;
        }

        if (!empty($query['categoryId'])) {
            $filters[] = 'p.category_id = ?';
            $values[] = Http::integer($query['categoryId']);
        }

        if (!empty($query['brandId'])) {
            $filters[] = 'p.brand_id = ?';
            $values[] = Http::integer($query['brandId']);
        }

        $minPrice = Http::number($query['minPrice'] ?? null);
        if ($minPrice !== null) {
            $filters[] = 'p.price >= ?';
            $values[] = $minPrice;
        }

        $maxPrice = Http::number($query['maxPrice'] ?? null);
        if ($maxPrice !== null) {
            $filters[] = 'p.price <= ?';
            $values[] = $maxPrice;
        }

        if (!empty($query['status'])) {
            $filters[] = 'p.status = ?';
            $values[] = $query['status'];
        }

        return [
            'clause' => $filters ? 'WHERE ' . implode(' AND ', $filters) : '',
            'values' => $values,
        ];
    }

    private static function mapRow(array $row): array
    {
        return [
            'id' => (int) $row['id'],
            'sku' => $row['sku'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => (float) $row['price'],
            'status' => $row['status'],
            'created_at' => $row['created_at'],
            'category' => [
                'id' => (int) $row['category_id'],
                'name' => $row['category_name'],
                'slug' => $row['category_slug'],
            ],
            'brand' => [
                'id' => (int) $row['brand_id'],
                'name' => $row['brand_name'],
                'country' => $row['brand_country'],
            ],
            'inventory' => [
                'stock' => (int) $row['stock'],
                'reserved' => (int) $row['reserved'],
                'warehouse_location' => $row['warehouse_location'],
            ],
            'reviews' => [
                'count' => (int) $row['review_count'],
                'avg_rating' => (float) $row['avg_rating'],
            ],
        ];
    }

    public static function index(array $query): void
    {
        $pdo = Database::connection();
        $page = max(1, Http::integer($query['page'] ?? null, 1));
        $perPage = min(100, max(1, Http::integer($query['perPage'] ?? null, 20)));
        $offset = ($page - 1) * $perPage;
        $filter = self::buildFilter($query);

        $sql = <<<SQL
SELECT
  p.id,
  p.sku,
  p.name,
  p.description,
  p.price,
  p.status,
  p.created_at,
  c.id AS category_id,
  c.name AS category_name,
  c.slug AS category_slug,
  b.id AS brand_id,
  b.name AS brand_name,
  b.country AS brand_country,
  i.stock,
  i.reserved,
  i.warehouse_location,
  COALESCE(rv.review_count, 0) AS review_count,
  COALESCE(rv.avg_rating, 0) AS avg_rating
FROM products p
INNER JOIN categories c ON c.id = p.category_id
INNER JOIN brands b ON b.id = p.brand_id
INNER JOIN inventory i ON i.product_id = p.id
LEFT JOIN (
  SELECT product_id, COUNT(*) AS review_count, ROUND(AVG(rating), 2) AS avg_rating
  FROM reviews
  GROUP BY product_id
) rv ON rv.product_id = p.id
{$filter['clause']}
ORDER BY p.created_at DESC, p.id DESC
LIMIT ? OFFSET ?
SQL;

        $statement = $pdo->prepare($sql);
        $statement->execute(array_merge($filter['values'], [$perPage, $offset]));
        $rows = $statement->fetchAll();

        $countStatement = $pdo->prepare('SELECT COUNT(*) AS total FROM products p ' . $filter['clause']);
        $countStatement->execute($filter['values']);
        $total = (int) ($countStatement->fetch()['total'] ?? 0);

        Http::json([
            'data' => array_map([self::class, 'mapRow'], $rows),
            'meta' => [
                'page' => $page,
                'perPage' => $perPage,
                'total' => $total,
            ],
        ]);
    }

    public static function show(int $id): void
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare(<<<SQL
SELECT
  p.id,
  p.sku,
  p.name,
  p.description,
  p.price,
  p.status,
  p.created_at,
  c.id AS category_id,
  c.name AS category_name,
  c.slug AS category_slug,
  b.id AS brand_id,
  b.name AS brand_name,
  b.country AS brand_country,
  i.stock,
  i.reserved,
  i.warehouse_location,
  COALESCE(rv.review_count, 0) AS review_count,
  COALESCE(rv.avg_rating, 0) AS avg_rating
FROM products p
INNER JOIN categories c ON c.id = p.category_id
INNER JOIN brands b ON b.id = p.brand_id
INNER JOIN inventory i ON i.product_id = p.id
LEFT JOIN (
  SELECT product_id, COUNT(*) AS review_count, ROUND(AVG(rating), 2) AS avg_rating
  FROM reviews
  GROUP BY product_id
) rv ON rv.product_id = p.id
WHERE p.id = ?
SQL);
        $statement->execute([$id]);
        $row = $statement->fetch();

        if (!$row) {
            Http::json(['message' => 'Product not found'], 404);
            return;
        }

        Http::json(['data' => self::mapRow($row)]);
    }

    public static function store(array $payload): void
    {
        $categoryId = Http::integer($payload['category_id'] ?? null);
        $brandId = Http::integer($payload['brand_id'] ?? null);
        $stock = Http::integer($payload['stock'] ?? null);
        $reserved = Http::integer($payload['reserved'] ?? null);
        $price = Http::number($payload['price'] ?? null) ?? 0;

        if (!$categoryId || !$brandId || empty($payload['sku']) || empty($payload['name'])) {
            Http::json(['message' => 'category_id, brand_id, sku, and name are required'], 400);
            return;
        }

        $pdo = Database::connection();
        $pdo->beginTransaction();

        try {
            $statement = $pdo->prepare(<<<SQL
INSERT INTO products (category_id, brand_id, sku, name, description, price, status)
VALUES (?, ?, ?, ?, ?, ?, ?)
SQL);
            $statement->execute([
                $categoryId,
                $brandId,
                $payload['sku'],
                $payload['name'],
                $payload['description'] ?? '',
                $price,
                $payload['status'] ?? 'active',
            ]);

            $productId = (int) $pdo->lastInsertId();
            $inventory = $pdo->prepare(<<<SQL
INSERT INTO inventory (product_id, stock, reserved, warehouse_location)
VALUES (?, ?, ?, ?)
SQL);
            $inventory->execute([
                $productId,
                $stock,
                $reserved,
                $payload['warehouse_location'] ?? 'WH-1',
            ]);

            $pdo->commit();
            Http::json(['message' => 'Product created', 'data' => ['id' => $productId]], 201);
        } catch (\Throwable $throwable) {
            $pdo->rollBack();
            throw $throwable;
        }
    }

    public static function update(int $id, array $payload): void
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();

        try {
            $exists = $pdo->prepare('SELECT id FROM products WHERE id = ?');
            $exists->execute([$id]);
            if (!$exists->fetch()) {
                $pdo->rollBack();
                Http::json(['message' => 'Product not found'], 404);
                return;
            }

            $product = $pdo->prepare(<<<SQL
UPDATE products
SET name = COALESCE(?, name),
    description = COALESCE(?, description),
    price = COALESCE(?, price),
    status = COALESCE(?, status)
WHERE id = ?
SQL);
            $product->execute([
                $payload['name'] ?? null,
                $payload['description'] ?? null,
                Http::number($payload['price'] ?? null),
                $payload['status'] ?? null,
                $id,
            ]);

            $inventory = $pdo->prepare(<<<SQL
UPDATE inventory
SET stock = COALESCE(?, stock),
    reserved = COALESCE(?, reserved),
    warehouse_location = COALESCE(?, warehouse_location)
WHERE product_id = ?
SQL);
            $inventory->execute([
                array_key_exists('stock', $payload) ? Http::integer($payload['stock']) : null,
                array_key_exists('reserved', $payload) ? Http::integer($payload['reserved']) : null,
                $payload['warehouse_location'] ?? null,
                $id,
            ]);

            $pdo->commit();
            Http::json(['message' => 'Product updated', 'data' => ['id' => $id]]);
        } catch (\Throwable $throwable) {
            $pdo->rollBack();
            throw $throwable;
        }
    }

    public static function destroy(int $id): void
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();

        try {
            $exists = $pdo->prepare('SELECT id FROM products WHERE id = ?');
            $exists->execute([$id]);
            if (!$exists->fetch()) {
                $pdo->rollBack();
                Http::json(['message' => 'Product not found'], 404);
                return;
            }

            $pdo->prepare('DELETE FROM reviews WHERE product_id = ?')->execute([$id]);
            $pdo->prepare('DELETE FROM inventory WHERE product_id = ?')->execute([$id]);
            $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
            $pdo->commit();

            Http::json(['message' => 'Product deleted', 'data' => ['id' => $id]]);
        } catch (\Throwable $throwable) {
            $pdo->rollBack();
            throw $throwable;
        }
    }
}
