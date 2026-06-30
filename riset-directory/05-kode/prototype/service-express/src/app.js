import express from 'express';
import routes from './routes/products.js';

const app = express();

app.use(express.json({ limit: '1mb' }));
app.use(routes);

app.use((error, req, res, next) => {
  const status = error.statusCode || error.status || 500;
  res.status(status).json({
    message: error.message || 'Internal server error',
  });
});

export default app;
