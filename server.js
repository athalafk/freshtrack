require('dotenv').config();
const express = require('express');
const cors = require('cors');

const authRoutes = require('./routes/authRoutes');
const barangRoutes = require('./routes/barangRoutes');
const riwayatRoutes = require('./routes/riwayatRoutes');


const app = express();
app.use(cors());
app.use(express.json());

app.use('/api/auth', authRoutes);
app.use('/api/barang', barangRoutes);
app.use('/api/riwayat', riwayatRoutes);

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`Server berjalan di http://localhost:${PORT}`));
