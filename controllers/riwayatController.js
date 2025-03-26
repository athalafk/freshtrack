const db = require("../config/db");

exports.getRiwayat = (req, res) => {
    db.query(
        "SELECT r.id, b.nama_barang, r.perubahan, r.stok_sebelumnya, r.stok_baru, r.satuan, r.created_at, r.jumlah FROM riwayat r JOIN barang b ON r.barang_id = b.id ORDER BY r.created_at DESC",
        (err, results) => {
            if (err) return res.status(500).json({ error: err.message });
            res.json(results);
        }
    );
};
