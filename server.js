require('dotenv').config();
const express = require('express');
const cors = require('cors');

const authRoutes = require('./routes/authRoutes');
const barangRoutes = require('./routes/barangRoutes');
const riwayatRoutes = require('./routes/riwayatRoutes');

const app = express();
app.use(cors());
app.use(express.json());

app.get('/', (req, res) => {
    res.send('Selamat datang di freshtrack!');
  });

app.use('/api/auth', authRoutes);
app.use('/api/barang', barangRoutes);
app.use('/api/riwayat', riwayatRoutes);

const PORT = process.env.PORT || 3000;
const HOST = '0.0.0.0'; 

app.listen(PORT, HOST, () => console.log(`Server berjalan di http://${HOST}:${PORT}`));