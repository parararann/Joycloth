# 🚀 Panduan Instalasi Web Sablon

## Prasyarat

Pastikan Anda memiliki software berikut terinstal:
- **Laragon** (direkomendasikan) atau **XAMPP**
- **PHP 8.2+**
- **Composer**
- **Node.js 18+** & **npm**
- **MySQL**

---

## Langkah Instalasi

### 1. Install Dependensi PHP (Composer)

Buka terminal di folder proyek `c:\Users\ASUS\Documents\Web Sablon`, lalu jalankan:

```bash
composer install
```

### 2. Generate APP_KEY

```bash
php artisan key:generate
```

### 3. Buat Database

Buka **phpMyAdmin** atau **MySQL CLI**, lalu buat database:

```sql
CREATE DATABASE web_sablon CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Konfigurasi .env

File `.env` sudah tersedia. Sesuaikan bagian berikut:

```env
DB_DATABASE=web_sablon
DB_USERNAME=root
DB_PASSWORD=          # isi password MySQL Anda jika ada

# Rekening bank Anda:
APP_BANK_NAME=Bank BCA
APP_BANK_ACCOUNT=1234567890
APP_ACCOUNT_HOLDER=Nama Anda
```

> **Catatan:** Tambahkan baris berikut di `.env` untuk info rekening bank:
> ```
> BANK_NAME="Bank BCA"
> BANK_ACCOUNT="1234567890"
> ACCOUNT_HOLDER="CV Sablon Jaya"
> ```
> Dan di `config/app.php` tambahkan:
> ```php
> 'bank_name'      => env('BANK_NAME', 'Bank BCA'),
> 'bank_account'   => env('BANK_ACCOUNT', '1234567890'),
> 'account_holder' => env('ACCOUNT_HOLDER', 'CV Sablon Jaya'),
> ```

### 5. Jalankan Migrasi & Seeder

```bash
php artisan migrate:fresh --seed
```

Ini akan membuat semua tabel dan mengisi data awal:
- **Admin**: `admin@joycloth.id` / `admin123`
- **User Demo**: `user@joycloth.id` / `user123`
- 6 Kategori + 6 Produk sampel

### 6. Buat Storage Link

```bash
php artisan storage:link
```

### 7. Install NPM & Build Frontend

```bash
npm install
npm run dev
```

### 8. Jalankan Server

```bash
php artisan serve
```

Buka browser: **http://localhost:8000**

---

## Akses Aplikasi

| Halaman | URL | Akun |
|---------|-----|------|
| Landing Page | http://localhost:8000 | - |
| Login | http://localhost:8000/login | - |
| Panel Admin | http://localhost:8000/admin | admin@joycloth.id / admin123 |
| Katalog | http://localhost:8000/katalog | - |

---

## Konfigurasi Rekening Bank

Edit file `.env` dan tambahkan:

```env
BANK_NAME="Bank BCA"
BANK_ACCOUNT="1234567890"
ACCOUNT_HOLDER="Nama Anda / CV Anda"
```

Kemudian edit `config/app.php` dan tambahkan di dalam array:

```php
'bank_name'      => env('BANK_NAME', 'Bank BCA'),
'bank_account'   => env('BANK_ACCOUNT', '1234567890'),
'account_holder' => env('ACCOUNT_HOLDER', 'CV Sablon Jaya'),
```

---

## Struktur Folder Penting

```
Web Sablon/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Frontend/     # Controller halaman user
│   │   │   ├── Admin/        # Controller panel admin
│   │   │   └── Auth/         # Controller autentikasi
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php
│   │   └── Requests/Auth/
│   ├── Models/               # User, Product, Order, dst
│   └── Providers/
├── database/
│   ├── migrations/           # 7 file migrasi
│   └── seeders/              # DatabaseSeeder
├── resources/
│   ├── css/app.css           # Tailwind + custom styles
│   ├── js/app.js             # Alpine.js + custom JS
│   └── views/
│       ├── layouts/          # app.blade.php, admin.blade.php
│       ├── auth/             # login, register
│       ├── products/         # katalog, detail
│       ├── cart/             # keranjang
│       ├── checkout/         # checkout
│       ├── orders/           # riwayat, detail, bayar
│       ├── chat/             # live chat user
│       ├── admin/            # semua halaman admin
│       └── welcome.blade.php # landing page
├── routes/
│   ├── web.php               # semua route
│   └── auth.php              # route autentikasi
├── .env                      # konfigurasi environment
└── README.md
```

---

## Fitur Lengkap

### 👤 Halaman User
- ✅ Landing page modern (hero, kategori, produk unggulan)
- ✅ Katalog dengan filter, search, sorting, pagination
- ✅ Detail produk + form order custom (ukuran, sablon, upload desain)
- ✅ Keranjang belanja (AJAX update quantity)
- ✅ Checkout dengan data pengiriman
- ✅ Upload bukti pembayaran transfer bank
- ✅ Tracking pesanan (timeline visual)
- ✅ Riwayat pesanan
- ✅ **Live Chat** dengan admin (polling real-time)

### 🔧 Panel Admin
- ✅ Dashboard dengan statistik & Chart.js
- ✅ CRUD Produk (dengan gambar, ukuran, jenis sablon)
- ✅ CRUD Kategori
- ✅ Manajemen pesanan (update status, konfirmasi)
- ✅ Verifikasi/tolak pembayaran + lihat bukti transfer
- ✅ Manajemen user (ubah role, hapus)
- ✅ **Panel Live Chat** (balas pesan pelanggan)

---

## Troubleshooting

**Error `Class not found`**: Jalankan `composer dump-autoload`

**Error storage permission**: Jalankan `php artisan storage:link`

**Halaman blank**: Cek file `.env` dan pastikan `APP_KEY` sudah terisi

**Database error**: Pastikan service MySQL berjalan dan database `web_sablon` sudah dibuat
