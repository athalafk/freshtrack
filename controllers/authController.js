const db = require('../config/db');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

exports.login = async (req, res) => {
  const { username, password } = req.body;

  try {
    const [results] = await db.query('SELECT * FROM users WHERE username = ?', [username]);

    if (results.length === 0) {
      return res.status(401).json({ error: 'Username atau password salah' });
    }

    const user = results[0];

    const isPasswordValid = await bcrypt.compare(password, user.password);
    if (!isPasswordValid) {
      return res.status(401).json({ error: 'Username atau password salah' });
    }

    if (!process.env.JWT_SECRET) {
      console.error("JWT_SECRET is not set");
      return res.status(500).json({ error: 'Token config error' });
    }

    const token = jwt.sign({ id: user.id, role: user.role }, process.env.JWT_SECRET, { expiresIn: '1h' });

    res.json({
      token,
      expiresIn: 3600,
      user: {
        id: user.id,
        username: user.username,
        role: user.role
      }
    });
  } catch (err) {
    console.error("Login error:", err);
    res.status(500).json({ error: 'Terjadi kesalahan saat login.' });
  }
};

// exports.logout = (req, res) => {
//   res.json({ message: 'Logout berhasil' });
// };
