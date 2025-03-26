const db = require('../config/db');

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
      
      const parsedResults = results.map(item => ({
        ...item,
        id: Number(item.id),
        total_stok: Number(item.total_stok)
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
       bg.tanggal_kadaluarsa,
       DATEDIFF(bg.tanggal_kadaluarsa, CURDATE()) AS hari_menuju_kadaluarsa
     FROM batch_barang bg
     JOIN barang b ON bg.barang_id = b.id
     ORDER BY bg.tanggal_kadaluarsa ASC`,
    (err, results) => {
      if (err) {
        console.error('Error getBatchBarang:', err);
        return res.status(500).json({ error: err.message });
      }
      console.log('Data batch:', results);
      res.json(results);
    }
  );
};