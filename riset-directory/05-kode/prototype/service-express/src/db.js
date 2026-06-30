import mysql from 'mysql2/promise';
import { config } from './config.js';

let pool;

export function getPool() {
  if (!pool) {
    pool = mysql.createPool(config.db);
  }
  return pool;
}
