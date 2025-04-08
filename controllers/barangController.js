const db = require("../config/db");

exports.getBarang = (req, res) => {
  db.query(
    `SELECT 
       b.id, 
       b.nama_barang, 
       b.satuan,
       CAST(IFNULL(SUM(bg.stok), 0) AS UNSIGNED) AS total_stok
     FROM barang b
     LEFT JOIN batch_barang bg ON b.id = bg.barang_id
     GROUP BY b.id
     ORDER BY b.nama_barang ASC`,
    (err, results) => {
      if (err) return res.status(500).json({ error: err.message });

      const parsedResults = results.map((item) => ({
        ...item,
        id: Number(item.id),
        total_stok: Number(item.total_stok),
      }));

      res.json(parsedResults);
    }
  );
};

exports.getBatchBarang = (req, res) => {
  db.query(
    `SELECT 
      bg.id,
      bg.barang_id,
      b.nama_barang,
      bg.stok,
      b.satuan,
      CONVERT_TZ(bg.tanggal_kadaluarsa, '+00:00', '+07:00') AS tanggal_kadaluarsa,
      DATEDIFF(
        DATE(CONVERT_TZ(bg.tanggal_kadaluarsa, '+00:00', '+07:00')),
        DATE(CONVERT_TZ(UTC_DATE(), '+00:00', '+07:00'))
      ) AS hari_menuju_kadaluarsa
    FROM batch_barang bg
    JOIN barang b ON bg.barang_id = b.id
    ORDER BY bg.tanggal_kadaluarsa ASC`,
    (err, results) => {
      if (err) {
        console.error("Error getBatchBarang:", err);
        return res.status(500).json({ error: err.message });
      }

      const formattedResults = results.map(item => ({
        ...item,
        tanggal_kadaluarsa: new Date(item.tanggal_kadaluarsa).toISOString(),
      }));

      console.log("Data batch:", formattedResults);
      res.json(formattedResults);
    }
  );
};

exports.updateBarang = (req, res) => {
  const { id } = req.params;
  const { nama_barang, satuan } = req.body;

  console.log("Update Barang:", id, nama_barang, satuan); // Debugging

  if (!nama_barang || !satuan) {
    return res
      .status(400)
      .json({ error: "Nama barang dan satuan wajib diisi." });
  }

  const query = "UPDATE barang SET nama_barang = ?, satuan = ? WHERE id = ?";
  db.query(query, [nama_barang, satuan, id], (err, result) => {
    if (err) {
      return res.status(500).json({ error: err.message });
    }

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: "Barang tidak ditemukan." });
    }

    res.json({ message: "Barang berhasil diperbarui." });
  });
};
