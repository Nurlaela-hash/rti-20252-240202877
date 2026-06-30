import app from './app.js';
import { config } from './config.js';
import { getPool } from './db.js';

const server = app.listen(config.port, async () => {
  try {
    const pool = getPool();
    await pool.query('SELECT 1');
    console.log(`Express service listening on port ${config.port}`);
  } catch (error) {
    console.error('Failed to verify database connection', error);
  }
});

process.on('SIGTERM', () => {
  server.close(() => process.exit(0));
});

process.on('SIGINT', () => {
  server.close(() => process.exit(0));
});
