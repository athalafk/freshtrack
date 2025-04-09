const express = require("express");
const { getRiwayat } = require("../controllers/riwayatController");
const { authMiddleware, authorizeRoles } = require("../middleware/authMiddleware");

const router = express.Router();

router.use(authMiddleware);

router.get("/", authorizeRoles("admin"), getRiwayat);

module.exports = router;
