import { getPool } from '../db.js';

function parseInteger(value, fallback) {
  const parsed = Number.parseInt(value, 10);
  return Number.isFinite(parsed) ? parsed : fallback;
}

function parseNumber(value) {
  if (value === undefined || value === null || value === '') {
    return null;
  }
  const parsed = Number(value);
  return Number.isFinite(parsed) ? parsed : null;
}

function buildFilter(query) {
  const filters = [];
  const values = [];

  if (query.search) {
    filters.push('(p.name LIKE ? OR p.sku LIKE ? OR p.description LIKE ?)');
    const search = `%${query.search}%`;
    values.push(search, search, search);
  }

  if (query.categoryId) {
    filters.push('p.category_id = ?');
    values.push(parseInteger(query.categoryId, 0));
  }

  if (query.brandId) {
    filters.push('p.brand_id = ?');
    values.push(parseInteger(query.brandId, 0));
  }

  const minPrice = parseNumber(query.minPrice);
  if (minPrice !== null) {
    filters.push('p.price >= ?');
    values.push(minPrice);
  }

  const maxPrice = parseNumber(query.maxPrice);
  if (maxPrice !== null) {
    filters.push('p.price <= ?');
    values.push(maxPrice);
  }

  if (query.status) {
    filters.push('p.status = ?');
    values.push(query.status);
  }

  return { clause: filters.length ? `WHERE ${filters.join(' AND ')}` : '', values };
}

function mapProductRow(row) {
  return {
    id: row.id,
    sku: row.sku,
    name: row.name,
    description: row.description,
    price: Number(row.price),
    status: row.status,
    created_at: row.created_at,
    category: {
      id: row.category_id,
      name: row.category_name,
      slug: row.category_slug,
    },
    brand: {
      id: row.brand_id,
      name: row.brand_name,
      country: row.brand_country,
    },
    inventory: {
      stock: row.stock,
      reserved: row.reserved,
      warehouse_location: row.warehouse_location,
    },
    reviews: {
      count: Number(row.review_count),
      avg_rating: Number(row.avg_rating),
    },
  };
}

export async function listProducts(req, res, next) {
  try {
    const pool = getPool();
    const page = Math.max(1, parseInteger(req.query.page, 1));
    const perPage = Math.min(100, Math.max(1, parseInteger(req.query.perPage, 20)));
    const offset = (page - 1) * perPage;
    const filter = buildFilter(req.query);

    const sql = `
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
      ${filter.clause}
      ORDER BY p.created_at DESC, p.id DESC
      LIMIT ? OFFSET ?
    `;

    const [rows] = await pool.query(sql, [...filter.values, perPage, offset]);
    const [countRows] = await pool.query(
      `SELECT COUNT(*) AS total FROM products p ${filter.clause}`,
      filter.values,
    );

    res.json({
      data: rows.map(mapProductRow),
      meta: {
        page,
        perPage,
        total: countRows[0].total,
      },
    });
  } catch (error) {
    next(error);
  }
}

export async function showProduct(req, res, next) {
  try {
    const pool = getPool();
    const id = parseInteger(req.params.id, 0);
    const [rows] = await pool.query(
      `
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
      `,
      [id],
    );

    if (rows.length === 0) {
      res.status(404).json({ message: 'Product not found' });
      return;
    }

    res.json({ data: mapProductRow(rows[0]) });
  } catch (error) {
    next(error);
  }
}

export async function createProduct(req, res, next) {
  const pool = getPool();
  const connection = await pool.getConnection();

  try {
    const payload = req.body ?? {};
    const categoryId = parseInteger(payload.category_id, 0);
    const brandId = parseInteger(payload.brand_id, 0);
    const stock = parseInteger(payload.stock, 0);
    const reserved = parseInteger(payload.reserved, 0);

    if (!categoryId || !brandId || !payload.sku || !payload.name) {
      res.status(400).json({ message: 'category_id, brand_id, sku, and name are required' });
      return;
    }

    await connection.beginTransaction();
    const [productResult] = await connection.query(
      `INSERT INTO products (category_id, brand_id, sku, name, description, price, status)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [
        categoryId,
        brandId,
        payload.sku,
        payload.name,
        payload.description || '',
        parseNumber(payload.price) ?? 0,
        payload.status || 'active',
      ],
    );

    const productId = productResult.insertId;
    await connection.query(
      `INSERT INTO inventory (product_id, stock, reserved, warehouse_location)
       VALUES (?, ?, ?, ?)`,
      [productId, stock, reserved, payload.warehouse_location || 'WH-1'],
    );

    await connection.commit();
    res.status(201).json({
      message: 'Product created',
      data: {
        id: productId,
        sku: payload.sku,
      },
    });
  } catch (error) {
    await connection.rollback();
    next(error);
  } finally {
    connection.release();
  }
}

export async function updateProduct(req, res, next) {
  const pool = getPool();
  const connection = await pool.getConnection();

  try {
    const id = parseInteger(req.params.id, 0);
    const payload = req.body ?? {};

    await connection.beginTransaction();
    const [existing] = await connection.query('SELECT id FROM products WHERE id = ?', [id]);
    if (existing.length === 0) {
      await connection.rollback();
      res.status(404).json({ message: 'Product not found' });
      return;
    }

    await connection.query(
      `UPDATE products
       SET name = COALESCE(?, name),
           description = COALESCE(?, description),
           price = COALESCE(?, price),
           status = COALESCE(?, status)
       WHERE id = ?`,
      [payload.name ?? null, payload.description ?? null, parseNumber(payload.price), payload.status ?? null, id],
    );

    await connection.query(
      `UPDATE inventory
       SET stock = COALESCE(?, stock),
           reserved = COALESCE(?, reserved),
           warehouse_location = COALESCE(?, warehouse_location)
       WHERE product_id = ?`,
      [
        payload.stock !== undefined ? parseInteger(payload.stock, 0) : null,
        payload.reserved !== undefined ? parseInteger(payload.reserved, 0) : null,
        payload.warehouse_location ?? null,
        id,
      ],
    );

    await connection.commit();
    res.json({ message: 'Product updated', data: { id } });
  } catch (error) {
    await connection.rollback();
    next(error);
  } finally {
    connection.release();
  }
}

export async function deleteProduct(req, res, next) {
  const pool = getPool();
  const connection = await pool.getConnection();

  try {
    const id = parseInteger(req.params.id, 0);
    await connection.beginTransaction();
    const [existing] = await connection.query('SELECT id FROM products WHERE id = ?', [id]);
    if (existing.length === 0) {
      await connection.rollback();
      res.status(404).json({ message: 'Product not found' });
      return;
    }

    await connection.query('DELETE FROM reviews WHERE product_id = ?', [id]);
    await connection.query('DELETE FROM inventory WHERE product_id = ?', [id]);
    await connection.query('DELETE FROM products WHERE id = ?', [id]);
    await connection.commit();

    res.json({ message: 'Product deleted', data: { id } });
  } catch (error) {
    await connection.rollback();
    next(error);
  } finally {
    connection.release();
  }
}
