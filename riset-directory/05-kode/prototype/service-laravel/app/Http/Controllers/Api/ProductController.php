<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class ProductController
{
    private function buildFilter(array $query): array
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
            $values[] = (int) $query['categoryId'];
        }

        if (!empty($query['brandId'])) {
            $filters[] = 'p.brand_id = ?';
            $values[] = (int) $query['brandId'];
        }

        if (isset($query['minPrice']) && $query['minPrice'] !== '') {
            $filters[] = 'p.price >= ?';
            $values[] = (float) $query['minPrice'];
        }

        if (isset($query['maxPrice']) && $query['maxPrice'] !== '') {
            $filters[] = 'p.price <= ?';
            $values[] = (float) $query['maxPrice'];
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

    private function mapRow(object|array $row): array
    {
        $data = (array) $row;

        return [
            'id' => (int) $data['id'],
            'sku' => $data['sku'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => (float) $data['price'],
            'status' => $data['status'],
            'created_at' => $data['created_at'],
            'category' => [
                'id' => (int) $data['category_id'],
                'name' => $data['category_name'],
                'slug' => $data['category_slug'],
            ],
            'brand' => [
                'id' => (int) $data['brand_id'],
                'name' => $data['brand_name'],
                'country' => $data['brand_country'],
            ],
            'inventory' => [
                'stock' => (int) $data['stock'],
                'reserved' => (int) $data['reserved'],
                'warehouse_location' => $data['warehouse_location'],
            ],
            'reviews' => [
                'count' => (int) $data['review_count'],
                'avg_rating' => (float) $data['avg_rating'],
            ],
        ];
    }

    public function index(Request $request): JsonResponse
    {
        $page = max(1, (int) $request->query('page', 1));
        $perPage = min(100, max(1, (int) $request->query('perPage', 20)));
        $offset = ($page - 1) * $perPage;
        $filter = $this->buildFilter($request->query());

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

        $rows = DB::select($sql, [...$filter['values'], $perPage, $offset]);
        $countRow = DB::selectOne('SELECT COUNT(*) AS total FROM products p ' . $filter['clause'], $filter['values']);

        return response()->json([
            'data' => array_map([$this, 'mapRow'], $rows),
            'meta' => [
                'page' => $page,
                'perPage' => $perPage,
                'total' => (int) ($countRow->total ?? 0),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $rows = DB::select(<<<SQL
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
SQL, [$id]);

        if ($rows === []) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(['data' => $this->mapRow($rows[0])]);
    }

    public function store(Request $request): JsonResponse
    {
        $payload = $request->all();
        $categoryId = (int) ($payload['category_id'] ?? 0);
        $brandId = (int) ($payload['brand_id'] ?? 0);

        if (!$categoryId || !$brandId || empty($payload['sku']) || empty($payload['name'])) {
            return response()->json(['message' => 'category_id, brand_id, sku, and name are required'], 400);
        }

        $productId = DB::transaction(function () use ($payload, $categoryId, $brandId): int {
            $productId = (int) DB::table('products')->insertGetId([
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'sku' => $payload['sku'],
                'name' => $payload['name'],
                'description' => $payload['description'] ?? '',
                'price' => (float) ($payload['price'] ?? 0),
                'status' => $payload['status'] ?? 'active',
            ]);

            DB::table('inventory')->insert([
                'product_id' => $productId,
                'stock' => (int) ($payload['stock'] ?? 0),
                'reserved' => (int) ($payload['reserved'] ?? 0),
                'warehouse_location' => $payload['warehouse_location'] ?? 'WH-1',
            ]);

            return $productId;
        });

        return response()->json([
            'message' => 'Product created',
            'data' => ['id' => $productId],
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $payload = $request->all();

        $updated = DB::transaction(function () use ($payload, $id): bool {
            $exists = DB::table('products')->where('id', $id)->exists();
            if (!$exists) {
                return false;
            }

            DB::table('products')->where('id', $id)->update([
                'name' => $payload['name'] ?? DB::raw('name'),
                'description' => $payload['description'] ?? DB::raw('description'),
                'price' => array_key_exists('price', $payload) ? (float) $payload['price'] : DB::raw('price'),
                'status' => $payload['status'] ?? DB::raw('status'),
            ]);

            DB::table('inventory')->where('product_id', $id)->update([
                'stock' => array_key_exists('stock', $payload) ? (int) $payload['stock'] : DB::raw('stock'),
                'reserved' => array_key_exists('reserved', $payload) ? (int) $payload['reserved'] : DB::raw('reserved'),
                'warehouse_location' => $payload['warehouse_location'] ?? DB::raw('warehouse_location'),
            ]);

            return true;
        });

        if (!$updated) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(['message' => 'Product updated', 'data' => ['id' => $id]]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = DB::transaction(function () use ($id): bool {
            $exists = DB::table('products')->where('id', $id)->exists();
            if (!$exists) {
                return false;
            }

            DB::table('reviews')->where('product_id', $id)->delete();
            DB::table('inventory')->where('product_id', $id)->delete();
            DB::table('products')->where('id', $id)->delete();

            return true;
        });

        if (!$deleted) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(['message' => 'Product deleted', 'data' => ['id' => $id]]);
    }
}
