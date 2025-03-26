const db = require('../config/db');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

exports.login = (req, res) => {
  const { username, password } = req.body;

  db.query('SELECT * FROM users WHERE username = ?', [username], (err, results) => {
    if (err || results.length === 0) {
      return res.status(401).json({ error: 'User tidak ditemukan' });
    }

    const user = results[0];
    if (!bcrypt.compareSync(password, user.password)) {
      return res.status(401).json({ error: 'Password salah' });
    }

    const token = jwt.sign({ id: user.id }, process.env.JWT_SECRET, { expiresIn: '1h' });

    res.json({
      token,
      user: {
        id: user.id,
        username: user.username,
        role: user.role
      }
    });
  });
};

// exports.logout = (req, res) => {
//   res.json({ message: 'Logout berhasil' });
// };