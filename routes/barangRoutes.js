const express = require("express");
const {
  getBarang,
  getBatchBarang,
  updateBarang,
} = require("../controllers/barangController");

const router = express.Router();

router.get("/", getBarang);
router.get("/batch-barang", getBatchBarang);
router.put("/:id", updateBarang);

module.exports = router;
