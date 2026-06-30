import fs from 'node:fs';
import path from 'node:path';
import mysql from 'mysql2/promise';
import dotenv from 'dotenv';

dotenv.config();

const rootDir = process.cwd();
const schemaPath = path.join(rootDir, 'db', 'schema.sql');
const schemaSql = fs.readFileSync(schemaPath, 'utf8');

const config = {
  host: process.env.DB_HOST || '127.0.0.1',
  port: Number(process.env.DB_PORT || 3306),
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || 'root',
  database: process.env.DB_NAME || 'benchmark_rti',
  multipleStatements: true,
  connectionLimit: 1,
};

const totalProducts = Number(process.env.TOTAL_PRODUCTS || 100000);
const reviewsPerProduct = Number(process.env.REVIEWS_PER_PRODUCT || 1);
const seedValue = Number(process.env.SEED || 42);
const batchSize = Number(process.env.BATCH_SIZE || 1000);
const resetDb = String(process.env.RESET_DB || 'true').toLowerCase() !== 'false';

function mulberry32(seed) {
  let t = seed >>> 0;
  return function nextRandom() {
    t += 0x6d2b79f5;
    let value = Math.imul(t ^ (t >>> 15), 1 | t);
    value ^= value + Math.imul(value ^ (value >>> 7), 61 | value);
    return ((value ^ (value >>> 14)) >>> 0) / 4294967296;
  };
}

function pick(array, index) {
  return array[index % array.length];
}

function formatCurrency(value) {
  return Number(value.toFixed(2));
}

async function main() {
  const connection = await mysql.createConnection({
    host: config.host,
    port: config.port,
    user: config.user,
    password: config.password,
    multipleStatements: true,
  });

  await connection.query(`CREATE DATABASE IF NOT EXISTS \`${config.database}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci`);
  await connection.changeUser({ database: config.database });

  if (resetDb) {
    await connection.query('SET FOREIGN_KEY_CHECKS = 0');
    await connection.query('DROP TABLE IF EXISTS reviews');
    await connection.query('DROP TABLE IF EXISTS inventory');
    await connection.query('DROP TABLE IF EXISTS products');
    await connection.query('DROP TABLE IF EXISTS brands');
    await connection.query('DROP TABLE IF EXISTS categories');
    await connection.query('SET FOREIGN_KEY_CHECKS = 1');
  }

  await connection.query(schemaSql);

  const random = mulberry32(seedValue);
  const categories = Array.from({ length: 50 }, (_, index) => [
    `Category ${String(index + 1).padStart(2, '0')}`,
    `category-${String(index + 1).padStart(2, '0')}`,
  ]);
  const brands = Array.from({ length: 100 }, (_, index) => [
    `Brand ${String(index + 1).padStart(3, '0')}`,
    pick(['Indonesia', 'Singapore', 'Japan', 'Germany', 'USA', 'Malaysia'], index),
  ]);

  await connection.query('DELETE FROM reviews');
  await connection.query('DELETE FROM inventory');
  await connection.query('DELETE FROM products');
  await connection.query('DELETE FROM brands');
  await connection.query('DELETE FROM categories');

  await insertBatch(connection, 'INSERT INTO categories (name, slug) VALUES ?', categories);
  await insertBatch(connection, 'INSERT INTO brands (name, country) VALUES ?', brands);

  const productRows = [];
  const inventoryRows = [];
  const reviewRows = [];

  for (let index = 1; index <= totalProducts; index += 1) {
    const categoryId = 1 + ((index - 1) % 50);
    const brandId = 1 + ((index - 1) % 100);
    const price = formatCurrency(10000 + random() * 9000000);
    const status = index % 5 === 0 ? 'inactive' : 'active';
    const name = `Product ${String(index).padStart(6, '0')}`;
    const sku = `SKU-${String(index).padStart(8, '0')}`;

    productRows.push([
      categoryId,
      brandId,
      sku,
      name,
      `Deterministic description for ${name}`,
      price,
      status,
    ]);

    inventoryRows.push([
      index,
      Math.floor(random() * 1000),
      Math.floor(random() * 50),
      `WH-${1 + ((index - 1) % 4)}`,
    ]);

    for (let reviewIndex = 0; reviewIndex < reviewsPerProduct; reviewIndex += 1) {
      reviewRows.push([
        index,
        1 + Math.floor(random() * 5),
        `Review ${reviewIndex + 1} for ${name}`,
        `Seeded review body for ${name}`,
      ]);
    }

    if (productRows.length >= batchSize) {
      await flushProductBatch(connection, productRows, inventoryRows, reviewRows);
    }
  }

  await flushProductBatch(connection, productRows, inventoryRows, reviewRows);
  await connection.end();
  console.log(`Seed completed for ${totalProducts} products with seed ${seedValue}.`);
}

async function insertBatch(connection, statement, rows) {
  if (rows.length === 0) {
    return;
  }
  await connection.query(statement, [rows]);
}

async function flushProductBatch(connection, productRows, inventoryRows, reviewRows) {
  if (productRows.length === 0) {
    return;
  }
  await connection.beginTransaction();
  try {
    const [productResult] = await connection.query(
      'INSERT INTO products (category_id, brand_id, sku, name, description, price, status) VALUES ?',
      [productRows],
    );

    const startId = Number(productResult.insertId);
    const insertedCount = productRows.length;

    const inventoryPayload = inventoryRows.map((row, offset) => [startId + offset, ...row.slice(1)]);
    const reviewPayload = reviewRows.map((row, offset) => [startId + Math.floor(offset / Math.max(1, reviewsPerProduct)), ...row.slice(1)]);

    if (inventoryPayload.length > 0) {
      await connection.query(
        'INSERT INTO inventory (product_id, stock, reserved, warehouse_location) VALUES ?',
        [inventoryPayload],
      );
    }

    if (reviewPayload.length > 0) {
      await connection.query(
        'INSERT INTO reviews (product_id, rating, title, body) VALUES ?',
        [reviewPayload],
      );
    }

    await connection.commit();
    productRows.splice(0, insertedCount);
    inventoryRows.splice(0, insertedCount);
    reviewRows.splice(0, insertedCount * reviewsPerProduct);
  } catch (error) {
    await connection.rollback();
    throw error;
  }
}

main().catch(error => {
  console.error(error);
  process.exit(1);
});
