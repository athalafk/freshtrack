const db = require("../config/db");

exports.getBarang = async (req, res) => {
  try {
    const [results] = await db.query(`
      SELECT 
        b.id,
        b.nama_barang,
        b.satuan,
        CAST(IFNULL(s.total_stok, 0) AS UNSIGNED) AS total_stok
      FROM barang b
      LEFT JOIN (
        SELECT barang_id, SUM(stok) AS total_stok
        FROM batch_barang
        GROUP BY barang_id
      ) s ON b.id = s.barang_id
      ORDER BY b.nama_barang ASC
    `);

    const parsedResults = results.map((item) => ({
      ...item,
      id: Number(item.id),
      total_stok: Number(item.total_stok),
    }));

    res.json(parsedResults);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
};

exports.getBatchBarang = async (req, res) => {
  try {
    const [results] = await db.query(`
      SELECT 
        bg.id,
        bg.barang_id,
        b.nama_barang,
        bg.stok,
        b.satuan,
        bg.tanggal_kadaluarsa,
        DATEDIFF(
          DATE(bg.tanggal_kadaluarsa),
          CURDATE()
        ) AS hari_menuju_kadaluarsa
      FROM batch_barang bg
      JOIN barang b ON bg.barang_id = b.id
      ORDER BY bg.tanggal_kadaluarsa ASC
    `);

    const formattedResults = results.map(item => ({
      ...item,
      tanggal_kadaluarsa: new Date(item.tanggal_kadaluarsa).toISOString(),
    }));    

    res.json(formattedResults);
  } catch (err) {
    console.error("Error getBatchBarang:", err);
    res.status(500).json({ error: err.message });
  }
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
