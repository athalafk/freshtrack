const express = require("express");
const { getRiwayat } = require("../controllers/riwayatController");

const router = express.Router();

router.get("/", getRiwayat);

module.exports = router;
