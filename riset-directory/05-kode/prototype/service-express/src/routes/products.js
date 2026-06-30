import { Router } from 'express';
import {
  createProduct,
  deleteProduct,
  listProducts,
  showProduct,
  updateProduct,
} from '../controllers/productsController.js';
import { healthCheck } from '../controllers/healthController.js';

const router = Router();

router.get('/health', healthCheck);
router.get('/api/products', listProducts);
router.get('/api/products/:id', showProduct);
router.post('/api/products', createProduct);
router.put('/api/products/:id', updateProduct);
router.delete('/api/products/:id', deleteProduct);

export default router;
