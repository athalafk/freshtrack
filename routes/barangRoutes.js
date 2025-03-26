const express = require('express');
const { getBarang, getBatchBarang } = require('../controllers/barangController');

const router = express.Router();

router.get('/', getBarang);
router.get('/batch-barang', getBatchBarang);

module.exports = router;
