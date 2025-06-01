# Freshtrack - Laravel (API dan Web)
FreshTrack adalah aplikasi manajemen inventaris yang dirancang untuk membantu restoran atau bisnis kuliner melacak masa kadaluarsa bahan baku, mengelola stok barang masuk dan keluar, serta memantau riwayat transaksi. Aplikasi ini terdiri dari backend API dan antarmuka Web yang dibangun menggunakan Laravel.

## Fitur Utama
- **Autentikasi Pengguna**: Sistem login untuk admin dan staf. API menggunakan Laravel Sanctum untuk autentikasi berbasis token, sedangkan Web menggunakan session-based authentication.
- **Manajemen Barang (Web & API)**: CRUD (Create, Read, Update, Delete) untuk data master barang.
- **Manajemen Batch Barang (Web & API)**: Setiap barang dapat memiliki beberapa batch dengan tanggal kedaluwarsa, sisa hari menuju tanggal kadaluarsa, dan jumlah stok yang berbeda.
- **Manajemen Stok (Web & API)**: Pencatatan barang masuk (menambah stok pada batch tertentu atau membuat batch baru) dan pencatatan barang keluar (mengurangi stok dari batch yang paling dekat kadaluarsanya).
- **Riwayat Transaksi (Web & API)**: Mencatat semua aktivitas penting seperti barang masuk, barang keluar, penambahan barang baru, pengeditan barang, dan penghapusan barang. Setiap transaksi mencatat item, tipe aksi, jumlah stok yang terlibat, dan aktor (pengguna yang melakukan aksi). Fitur filter transaksi berdasarkan rentang tanggal dan pencetakan PDF tersedia di antarmuka Web.
- **Antarmuka Web**: Menyediakan dashboard interaktif untuk manajemen inventori, transaksi, dan riwayat.
- **API Endpoints**: Menyediakan endpoint API yang aman untuk semua fungsionalitas utama.

## Teknologi yang Digunakan
- Framework: Laravel 12.x
- Database: MySQL
- Autentikasi API: Laravel Sanctum
- Autentikasi Web: Laravel Session
- PHP: Versi 8.2 atau lebih tinggi
- Composer: Untuk manajemen dependensi PHP
- Frontend Web: Blade, Tailwind CSS, Alpine.js

## Prasyarat
Sebelum memulai, pastikan Anda memiliki perangkat lunak berikut terinstal di sistem Anda:

- PHP (versi yang sesuai dengan Laravel 12, direkomendasikan 8.2+)
- Composer
- Database Server (MySQL)
- Node.js dan npm (untuk Vite dan dependensi frontend)
- Git (opsional, untuk kloning repositori)

## Instalasi
**1. Clone Repositori**:
```bash
git clone https://github.com/athalafk/freshtrack.git
cd freshtrack
```
Jika tidak, pastikan Anda berada di direktori root proyek Anda.

**2. Install Dependensi PHP**:
```bash
composer install
```

**3. Install Node.js**:
```bash
npm install
```

**4. Salin File Environment**:
Buat file .env dari contoh file .env.example:
```bash
copy .env.example .env
```

**5. Generate Kunci Aplikasi**:
```bash
php artisan key:generate
```

**6. Konfigurasi Database**:
Buka file .env dan sesuaikan pengaturan database berikut sesuai dengan konfigurasi server database Anda:
```bash
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=freshtrack    # Ganti dengan nama database Anda
DB_USERNAME=root          # Ganti dengan username database Anda
DB_PASSWORD=              # Ganti dengan password database Anda
```
Pastikan database dengan nama yang Anda tentukan sudah dibuat di server database Anda.

**7. Jalankan Migrasi Database**:
Perintah ini akan membuat semua tabel yang diperlukan di database Anda.
```bash
php artisan migrate
```

**8. Jalankan Database Seeder** (Opsional, tapi direkomendasikan untuk data awal):
Perintah ini akan mengisi database dengan data dummy (pengguna, barang, batch, transaksi) yang telah Anda definisikan di file seeder.
```bash
php artisan db:seed
```
Jika Anda ingin mereset database dan menjalankan migrasi serta seeder dari awal:
```bash
php artisan migrate:fresh --seed
```

**9. Build Aset Frontend**:
```bash
npm run build
```

**10. Jalankan Server Pengembangan Laravel**:
```bash
php artisan serve
```
Secara default, server akan berjalan di ```http://127.0.0.1:8000```.

## Struktur Web

Semua rute web (kecuali halaman login) dilindungi oleh autentikasi.

### Autentikasi (`/`)

- **GET** `/login`: Menampilkan halaman login.
- **POST** `/login`: Memproses permintaan login pengguna.
- **POST** `/logout`: Memproses permintaan logout pengguna.

### Inventori (`/inventori`)

- **GET** `/`: Menampilkan halaman utama inventori yang berisi daftar barang dan status kedaluwarsa batch barang. Mendukung pencarian dan pengurutan.
- **PUT** `/{barang}` _(Khusus Admin)_: Memperbarui data master barang tertentu.
- **DELETE** `/{barang}` _(Khusus Admin)_: Menghapus data master barang beserta semua batch terkait.

### Registrasi Barang (`/registrasi`) _(Khusus Admin)_

- **GET** `/`: Menampilkan formulir pendaftaran barang baru.
- **POST** `/`: Menyimpan data barang baru ke dalam database.

### Transaksi (`/transaksi`)

- **GET** `/barang-masuk`: Menampilkan formulir pencatatan transaksi barang masuk.
- **POST** `/barang-masuk`: Memproses dan menyimpan transaksi barang masuk. Menambah stok batch barang.
- **GET** `/barang-keluar`: Menampilkan formulir pencatatan transaksi barang keluar.
- **POST** `/barang-keluar`: Memproses dan menyimpan transaksi barang keluar. Mengurangi stok batch barang.

### Riwayat (`/riwayat`) _(Khusus Admin)_

- **GET** `/`: Menampilkan halaman riwayat transaksi. Mendukung filter berdasarkan rentang tanggal dan opsi cetak laporan dalam format PDF.

## Struktur API Endpoint Utama
Semua endpoint API berada di bawah prefix ```/api```.

### Autentikasi (`/auth`)

- **POST** `/login`: Login pengguna.
  - **Body**: `username`, `password`
  - **Respons Sukses**: Token akses, tipe token, detail pengguna.

- **POST** `/register` (Membutuhkan autentikasi admin): Registrasi pengguna baru.
  - **Body**: `username`, `password`, `role` (`admin` atau `staf`)
  - **Respons Sukses**: Pesan sukses, detail pengguna baru.

- **POST** `/logout` (Membutuhkan autentikasi): Logout pengguna (mencabut token saat ini).

### Barang (`/barang`)

- **GET** `/` (Membutuhkan autentikasi): Mendapatkan semua daftar barang beserta total stoknya.

- **GET** `/batch-barang` (Membutuhkan autentikasi): Mendapatkan semua daftar batch barang beserta informasi barang dan sisa hari kedaluwarsa.

- **POST** `/create` (Membutuhkan autentikasi): Membuat barang baru.
  - **Body**: `nama_barang`, `satuan`

- **PUT** `/update/{id}` (Membutuhkan autentikasi): Memperbarui data barang berdasarkan ID.
  - **Body**: `nama_barang`, `satuan`

- **DELETE** `/delete/{id}` (Membutuhkan autentikasi): Menghapus barang berdasarkan ID (beserta semua batch terkait).

- **POST** `/masuk` (Membutuhkan autentikasi): Mencatat barang masuk (menambah stok ke batch).
  - **Body**: `nama_barang` (harus sudah ada di master barang), `stok`, `tanggal_kadaluarsa`

- **POST** `/keluar` (Membutuhkan autentikasi): Mencatat barang keluar (mengurangi stok dari batch).
  - **Body**: `nama_barang` (harus sudah ada di master barang), `stok`

### Transaksi (`/transactions`)

- **GET** `/` (Membutuhkan autentikasi): Mendapatkan semua riwayat transaksi.

## Penggunaan API

- Semua endpoint yang membutuhkan autentikasi harus menyertakan token Sanctum di header `Authorization` dengan format `Bearer [TOKEN_ANDA]`.

- Untuk request yang mengirim data (POST, PUT), gunakan header `Content-Type: application/json` dan `Accept: application/json`.

## Kontributor
- Athala Farrastya Kamil
- Ahmad Qadir Jailani
- Raihan Ferdinand Khairuazfa
- Bayu Tiadi Nurul Fajar
- Muhammad Hilmi Fauzi
- Helmi Efendi Lubis

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
