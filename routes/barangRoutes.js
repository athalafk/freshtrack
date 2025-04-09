const express = require("express");
const {
  getBarang,
  getBatchBarang,
  updateBarang,
} = require("../controllers/barangController");

const { authMiddleware, authorizeRoles } = require("../middleware/authMiddleware");

const router = express.Router();

router.use(authMiddleware);

router.get("/", getBarang);

router.get("/batch-barang", authorizeRoles("admin", "staf"), getBatchBarang);

router.put("/:id", authorizeRoles("admin", "staf"), updateBarang);

module.exports = router;
