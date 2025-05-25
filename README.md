# Freshtrack - Laravel (API dan Web)
FreshTrack adalah aplikasi manajemen inventaris yang dirancang untuk membantu restoran atau bisnis kuliner melacak masa kedaluwarsa bahan baku, mengelola stok barang masuk dan keluar, serta memantau riwayat transaksi. Backend ini dibangun menggunakan Laravel dan menyediakan API untuk diintegrasikan dengan aplikasi frontend.

## Fitur Utama
- Autentikasi Pengguna: Sistem login untuk admin dan staf dengan menggunakan Laravel Sanctum untuk autentikasi berbasis token.
- Manajemen Barang: CRUD (Create, Read, Update, Delete) untuk data master barang.
- Manajemen Batch Barang: Setiap barang dapat memiliki beberapa batch dengan tanggal kedaluwarsa dan menampilkan sisa hari menuju tanggal kadaluarsa serta jumlah stok yang berbeda
- Manajemen Stok: Pencatatan barang masuk (menambah stok pada batch tertentu atau membuat batch baru) dan Pencatatan barang keluar (mengurangi stok pada batch yang paling dekat kadaluarsanya).
- Riwayat Transaksi: Mencatat semua aktivitas penting seperti barang masuk, barang keluar, penambahan barang baru, pengeditan barang, dan penghapusan barang. Setiap transaksi mencatat item, tipe aksi, jumlah stok yang terlibat, dan aktor (pengguna yang melakukan aksi). Filter transaksi berdasarkan rentang tanggal dan mencetak pdf.
- API Endpoints: Menyediakan endpoint API yang aman untuk semua fungsionalitas di atas.

## Teknologi yang Digunakan
- Framework: Laravel 12.x
- Database: MySQL
- Autentikasi API: Laravel Sanctum
- PHP: Versi 8.2 atau lebih tinggi
- Composer: Untuk manajemen dependensi PHP

## Prasyarat
Sebelum memulai, pastikan Anda memiliki perangkat lunak berikut terinstal di sistem Anda:

- PHP (versi yang sesuai dengan Laravel 12, direkomendasikan 8.2+)
- Composer
- Database Server (misalnya MySQL, MariaDB, PostgreSQL, atau SQLite)
- Git (opsional, untuk kloning repositori)

## Instalasi
**1. Clone Repositori**:
```bash
git clone https://github.com/athalafk/freshtrack-postman.git
cd freshtrack-backend
```
Jika tidak, pastikan Anda berada di direktori root proyek Anda.

**2. Install Dependensi PHP**:
```bash
composer install
```

**3. Salin File Environment**:
Buat file .env dari contoh file .env.example:
```bash
copy .env.example .env
```

**4. Generate Kunci Aplikasi**:
```bash
php artisan key:generate
```

**5. Konfigurasi Database**:
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

**6. Jalankan Migrasi Database**:
Perintah ini akan membuat semua tabel yang diperlukan di database Anda.
```bash
php artisan migrate
```

**7. Jalankan Database Seeder** (Opsional, tapi direkomendasikan untuk data awal):
Perintah ini akan mengisi database dengan data dummy (pengguna, barang, batch, transaksi) yang telah Anda definisikan di file seeder.
```bash
php artisan db:seed
```
Jika Anda ingin mereset database dan menjalankan migrasi serta seeder dari awal:
```bash
php artisan migrate:fresh --seed
```

**8. Jalankan Server Pengembangan Laravel**:
```bash
php artisan serve
```
Secara default, server akan berjalan di ```http://127.0.0.1:8000```.

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
