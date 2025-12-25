# 🚗 Koneksi Jasa - Web Application

Aplikasi web full-stack untuk platform "Koneksi Jasa" - marketplace jasa cuci mobil dan service AC yang dibuat dengan **Laravel 12** menggunakan **JWT Authentication** dan **Tailwind CSS**.

## 🎯 Overview

**Koneksi Jasa** adalah platform yang menghubungkan pengguna dengan penyedia jasa cuci mobil dan service AC profesional di daerah mereka. Aplikasi ini menyediakan sistem manajemen data dengan autentikasi JWT untuk keamanan maksimal.

### ✨ Fitur Utama
- **🔐 JWT Authentication**: Sistem autentikasi aman menggunakan JSON Web Token
- **👤 Register & Login**: Pendaftaran dan login pengguna dengan validasi
- **� Role System**: Admin dapat mengelola semua data, User hanya view & booking
- **🚗 CRUD Service Providers**: Manajemen lengkap data penyedia jasa
- **📂 CRUD Categories**: Manajemen kategori jasa (Cuci Mobil, Service AC)
- **📅 Booking System**: User dapat memesan service provider dengan kategori tertentu
- **📊 Booking History**: Admin dapat melihat log riwayat pemesanan semua user
- **📷 Upload Gambar**: Upload foto untuk service provider (max 2MB, JPEG/PNG/JPG/GIF/WEBP)
- **✅ Validasi Ketat**: Client-side (HTML5) dan server-side validation dengan custom error messages
- **📱 Format Telepon**: Regex validation untuk nomor telepon (hanya angka, +, -, (, ), spasi, min 10 digit)
- **📧 Email Support**: Field email untuk service providers dengan validasi format
- **🎯 Filter Kategori**: Filter service providers berdasarkan kategori di list dan dashboard
- **📈 Dashboard Analytics**: Stats per kategori dengan gradient cards & visual menarik
- **🔗 Relasi Database**: Categories, Service Providers, Bookings berelasi
- **🆔 UUID Identifier**: Setiap tabel utama memiliki UUID sebagai identifier unik
- **🎨 Tailwind CSS**: Styling modern dan responsive
- **📱 Responsive Design**: Tampilan yang optimal di semua perangkat

## 📁 Struktur Proyek

```
web-agus/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php              # API Authentication dengan JWT
│   │   │   ├── CategoryController.php          # API CRUD Categories
│   │   │   ├── ServiceProviderController.php   # API CRUD Service Providers
│   │   │   └── Web/
│   │   │       ├── AuthWebController.php       # Web Authentication
│   │   │       ├── DashboardController.php     # Dashboard utama
│   │   │       ├── CategoryWebController.php   # Web CRUD Categories
│   │   │       ├── ServiceProviderWebController.php # Web CRUD Service Providers
│   │   │       └── BookingController.php       # Web Booking System
│   │   └── Middleware/
│   │       └── AdminMiddleware.php             # Middleware untuk role admin
│   └── Models/
│       ├── User.php                   # Model User dengan JWT & UUID
│       ├── Category.php               # Model Category dengan UUID
│       ├── ServiceProvider.php        # Model Service Provider dengan UUID
│       └── Booking.php                # Model Booking dengan UUID
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2025_11_25_153716_create_categories_table.php
│   │   ├── 2025_11_25_153729_create_service_providers_table.php
│   │   ├── 2025_12_24_030423_add_uuid_to_users_table.php
│   │   ├── 2025_12_25_072145_add_role_to_users_table.php
│   │   ├── 2025_12_25_073459_create_bookings_table.php
│   │   └── 2025_12_25_082236_add_email_to_service_providers_table.php
│   └── seeders/
│       ├── CategorySeeder.php         # Seeder kategori awal
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── auth/                      # Login & Register views
│       ├── dashboard/                 # Dashboard view
│       ├── categories/                # CRUD Categories views
│       ├── service-providers/         # CRUD Service Providers views
│       ├── bookings/                  # Booking System views
│       └── layouts/
│           └── app.blade.php          # Layout utama
├── routes/
│   ├── web.php                        # Web routes (protected dengan auth)
│   └── api.php                        # API routes (protected dengan JWT)
├── config/
│   └── jwt.php                        # Konfigurasi JWT
├── .env                              # Environment variables
├── composer.json                     # Dependencies PHP/Laravel
├── package.json                      # Dependencies Node/Tailwind
└── README.md                         # Dokumentasi ini
```

## 🗄️ Database Schema

### Tabel Users
Menyimpan data pengguna aplikasi dengan autentikasi JWT.

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| id | BIGINT | Primary key (auto increment) |
| uuid | UUID | Unique identifier untuk routing |
| name | VARCHAR(255) | Nama lengkap pengguna |
| email | VARCHAR(255) | Email pengguna (unique) |
| password | VARCHAR(255) | Password ter-hash |
| created_at | TIMESTAMP | Waktu pembuatan record |
| updated_at | TIMESTAMP | Waktu update terakhir |

### Tabel Categories
Menyimpan data kategori layanan (cuci mobil, service AC, dll).

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| id | BIGINT | Primary key (auto increment) |
| uuid | UUID | Unique identifier untuk routing |
| name | VARCHAR(100) | Nama kategori (wajib) |
| description | TEXT | Deskripsi kategori |
| created_at | TIMESTAMP | Waktu pembuatan record |
| updated_at | TIMESTAMP | Waktu update terakhir |

### Tabel Service Providers
Menyimpan data penyedia jasa yang berelasi dengan kategori.

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| id | BIGINT | Primary key (auto increment) |
| uuid | UUID | Unique identifier untuk routing |
| name | VARCHAR(100) | Nama penyedia jasa (wajib, min 3 karakter) |
| category_id | BIGINT | Foreign key ke categories |
| phone | VARCHAR(20) | Nomor telepon (min 10 digit, regex: angka +, -, (, ), spasi) |
| email | VARCHAR(255) | Email provider (optional, format email valid) |
| address | TEXT | Alamat lengkap (max 500 karakter) |
| description | TEXT | Deskripsi layanan (max 1000 karakter) |
| photo | VARCHAR(255) | Path foto (max 2MB, JPEG/PNG/JPG/GIF/WEBP) |
| created_at | TIMESTAMP | Waktu pembuatan record |
| updated_at | TIMESTAMP | Waktu update terakhir |

**Relasi**: 
- `service_providers.category_id` → `categories.id` (Many-to-One)
- Cascade delete: Jika kategori dihapus, semua service providers terkait ikut terhapus

### Tabel Bookings
Menyimpan data pemesanan jasa dari user ke service provider.

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| id | BIGINT | Primary key (auto increment) |
| uuid | UUID | Unique identifier untuk routing |
| user_id | BIGINT | Foreign key ke users (siapa yang booking) |
| service_provider_id | BIGINT | Foreign key ke service_providers |
| category_id | BIGINT | Foreign key ke categories |
| booking_date | DATE | Tanggal booking |
| booking_time | TIME | Waktu booking |
| notes | TEXT | Catatan tambahan dari customer |
| customer_name | VARCHAR(100) | Nama customer (bisa beda dengan user) |
| customer_phone | VARCHAR(20) | Telepon customer |
| customer_address | TEXT | Alamat customer |
| status | ENUM | pending/confirmed/completed/cancelled |
| created_at | TIMESTAMP | Waktu pembuatan record |
| updated_at | TIMESTAMP | Waktu update terakhir |

**Relasi**: 
- `bookings.user_id` → `users.id` (Many-to-One)
- `bookings.service_provider_id` → `service_providers.id` (Many-to-One)
- `bookings.category_id` → `categories.id` (Many-to-One)
- Cascade delete: Jika user/provider/category dihapus, bookingnya ikut terhapus

## 🚀 Cara Instalasi & Menjalankan Project

### Prerequisites
Pastikan sudah terinstall:
- **PHP >= 8.2**
- **Composer**
- **Node.js & NPM**
- **MySQL/MariaDB**
- **Web Server** (Apache/Nginx) atau gunakan Laragon/XAMPP

### Langkah 1: Clone Repository
```bash
git clone <repository-url>
cd web-agus
```

### Langkah 2: Install Dependencies

**Install PHP Dependencies:**
```bash
composer install
```

**Install Node Dependencies:**
```bash
npm install
```

### Langkah 3: Konfigurasi Environment

**Copy file .env.example menjadi .env:**
```bash
copy .env.example .env
```
*atau di Linux/Mac:*
```bash
cp .env.example .env
```

**Edit file .env dan sesuaikan konfigurasi database:**
```env
APP_NAME="Koneksi Jasa"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=koneksi_jasa
DB_USERNAME=root
DB_PASSWORD=

JWT_SECRET=
```

### Langkah 4: Generate Application Key & JWT Secret
```bash
php artisan key:generate
php artisan jwt:secret
```

### Langkah 5: Buat Database
Buat database baru di MySQL dengan nama `koneksi_jasa`:
```sql
CREATE DATABASE koneksi_jasa;
```

### Langkah 6: Jalankan Migrasi & Seeder
```bash
php artisan migrate --seed
```

Perintah ini akan:
- Membuat semua tabel (users, categories, service_providers)
- Menambahkan kolom `role` (admin/user) ke tabel users
- Mengisi data awal untuk categories
- Membuat 2 user default:
  - **Admin**: admin@admin.com / admin123
  - **User**: user@test.com / user123

### Langkah 7: Buat Symbolic Link untuk Storage
```bash
php artisan storage:link
```

### Langkah 8: Build Assets (Tailwind CSS)
```bash
npm run build
```

*Untuk development dengan auto-reload:*
```bash
npm run dev
```

### Langkah 9: Jalankan Server
```bash
php artisan serve
```

Aplikasi akan berjalan di: **http://localhost:8000**

### Langkah 10: Akses Aplikasi

**Landing Page (Public):**
```
http://localhost:8000/
```

**Login:**
```
http://localhost:8000/login
```

**Register:**
```
http://localhost:8000/register
```

**Dashboard (Setelah Login):**
```
http://localhost:8000/dashboard
```

## 👤 Membuat User Pertama

### ⚡ Quick Start - Gunakan Akun Default

Setelah menjalankan seeder, Anda sudah memiliki 2 akun yang bisa langsung digunakan:

#### 🔑 Akun Admin (Full Access)
- **Email**: `admin@admin.com`
- **Password**: `admin123`
- **Akses**: Dapat mengelola semua data (CRUD Categories & Service Providers)

#### 👤 Akun User (View Only)
- **Email**: `user@tRegister Form (User Baru)
1. Buka http://localhost:8000/register
2. Isi form registrasi:
   - Nama Lengkap
   - Email
   - Password (minimal 6 karakter)
   - Konfirmasi Password
3. Klik "Daftar"
4. User baru akan memiliki role "user" (view only)
5. Login dengan credentials yang baru dibuat

### Cara 3: Melalui est.com`
- **Password**: `user123`
- **Akses**: Hanya dapat melihat data (tidak bisa create, edit, delete)

### Cara 1: Melalui Register Form
1. Buka http://localhost:8000/register
2. Isi form registrasi:
   - Nama Lengkap
   - Email
   - Password (minimal 6 karakter)
// Membuat user biasa
\App\Models\User::create([
    'name' => 'User Baru',
    'email' => 'user@example.com',
    'password' => bcrypt('password123'),
    'role' => 'user'
]);

// Membuat admin
\App\Models\User::create([
    'name' => 'Admin Baru',
    'email' => 'admin2@example.com',
    'password' => bcrypt('password123'),
    'role' => 'admin'
]);
```

## 🔐 Sistem Role & Permission

Project ini menggunakan **2 role berbeda**:

### 👑 Admin
- **Full Access**: Dapat melakukan CRUD (Create, Read, Update, Delete) pada semua data
- **Categories**: ✅ Tambah, ✅ Edit, ✅ Hapus, ✅ Lihat
- **Service Providers**: ✅ Tambah, ✅ Edit, ✅ Hapus, ✅ Lihat
- **Bookings**: ✅ Lihat semua booking dari semua user, ✅ Update status booking
- **Riwayat**: ✅ Dapat melihat log riwayat siapa saja yang memesan
- **Tampilan**: Badge "ADMIN" di navbar, menu "Riwayat Booking"
- **Default Account**: admin@admin.com / admin123

### 👤 User (Regular)
- **View Only**: Hanya dapat melihat data, tidak bisa melakukan perubahan pada master data
- **Categories**: ✅ Lihat (List & Detail)
- **Service Providers**: ✅ Lihat (List & Detail)
- **Bookings**: ✅ Dapat memesan service provider, ✅ Lihat booking sendiri, ✅ Batalkan booking sendiri (status pending)
- **Restrictions**: ❌ Tidak ada tombol Tambah/Edit/Hapus untuk Categories & Service Providers
- **Tampilan**: Menu "Pesan Jasa" dan "Booking Saya" di navbar
- **Default Account**: user@test.com / user123

### Implementasi Teknis
- Kolom `role` di tabel users: ENUM('admin', 'user')
- Middleware `admin` untuk proteksi route CRUD
- Helper methods: `isAdmin()` dan `isUser()` di User model
- Conditional rendering di Blade templates berdasarkan role

## 🎫 Fitur Booking System

### Cara Memesan Service Provider (User)

1. **Login sebagai User** (user@test.com / user123)

2. **Pilih Menu "Pesan Jasa"** di navbar

3. **Filter Kategori** (opsional):
   - Pilih kategori tertentu untuk melihat service provider yang sesuai
   - Atau pilih "Semua Kategori" untuk melihat semua provider

4. **Pilih Service Provider**:
   - Browse kartu service provider yang menampilkan foto, nama, kategori, dan kontak
   - Klik tombol "Pesan Sekarang" pada provider yang diinginkan

5. **Isi Form Booking**:
   - **Nama Lengkap**: Otomatis terisi dari akun user
   - **Nomor Telepon**: Masukkan nomor yang bisa dihubungi
   - **Alamat**: Alamat lengkap untuk pengerjaan jasa (opsional)
   - **Tanggal Booking**: Pilih tanggal (tidak bisa tanggal lalu)
   - **Waktu Booking**: Pilih jam
   - **Catatan Tambahan**: Permintaan khusus atau catatan untuk provider (opsional)

6. **Konfirmasi Pemesanan**: Klik "Konfirmasi Pemesanan"

7. **Lihat Booking**: Cek menu "Booking Saya" untuk melihat status booking

### Status Booking

| Status | Warna | Keterangan |
|--------|-------|------------|
| **Pending** | Kuning | Booking baru, menunggu konfirmasi admin |
| **Confirmed** | Biru | Booking dikonfirmasi, akan dikerjakan |
| **Completed** | Hijau | Pekerjaan selesai |
| **Cancelled** | Merah | Booking dibatalkan |

### Membatalkan Booking (User)

User dapat membatalkan booking **hanya dengan status Pending**:
1. Buka "Booking Saya"
2. Klik "Batalkan" pada booking yang ingin dibatalkan
3. Konfirmasi pembatalan

**Note**: Booking dengan status Confirmed/Completed tidak bisa dibatalkan oleh user.

### Mengelola Booking (Admin)

Admin memiliki akses penuh untuk mengelola semua booking:

1. **Lihat Riwayat Booking**: Menu "Riwayat Booking" menampilkan semua booking dari semua user

2. **Filter Status**: Filter booking berdasarkan status (Pending/Confirmed/Completed/Cancelled)

3. **Update Status**: 
   - Klik "Detail" pada booking
   - Scroll ke bawah ke form "Update Status Booking"
   - Pilih status baru (Pending → Confirmed → Completed)
   - Klik "Update Status"

4. **Informasi Lengkap**: Admin bisa melihat:
   - Data user yang memesan
   - Data customer (nama, telepon, alamat)
   - Service provider dan kategori yang dipilih
   - Tanggal & waktu booking
   - Catatan dari customer

### Dashboard Statistik Booking

**Dashboard Admin menampilkan**:
- Total semua booking
- Jumlah booking per status (Pending/Confirmed/Completed)
- **Service Providers per Kategori** dengan gradient cards
- 5 booking terbaru dari semua user

**Dashboard User menampilkan**:
- Total service providers dan kategori
- **Service Providers per Kategori** dengan gradient cards
- List service provider terbaru

**Fitur Dashboard per Kategori**:
- Setiap kategori ditampilkan dalam card dengan gradient background
- Menampilkan jumlah service providers per kategori
- Deskripsi kategori (jika ada)
- Link langsung ke filtered list service providers
- Responsive grid layout

## 📋 Validasi Form

### Validasi Service Provider

**Server-Side (Laravel):**
```php
'name' => 'required|string|min:3|max:100',
'category_id' => 'required|exists:categories,id',
'phone' => 'nullable|string|regex:/^[0-9+\-\(\)\s]+$/|min:10|max:20',
'email' => 'nullable|email|max:255',
'address' => 'nullable|string|max:500',
'description' => 'nullable|string|max:1000',
'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
```

**Client-Side (HTML5):**
- Input type `tel` untuk telepon dengan pattern `[0-9+\-\(\)\s]+`
- Input type `email` untuk email
- Attributes: `minlength`, `maxlength`, `required`, `pattern`
- Custom error messages untuk setiap validasi

**Format Telepon yang Valid:**
- ✅ `081234567890`
- ✅ `+62-812-3456-7890`
- ✅ `(021) 1234-5678`
- ✅ `+62 812 3456 7890`

**Format Telepon yang Ditolak:**
- ❌ `abc123def` (ada huruf)
- ❌ `0812.3456.7890` (ada titik)
- ❌ `0812@gmail` (karakter tidak valid)
- ❌ `123` (kurang dari 10 digit)

### Validasi Booking

**Server-Side (Laravel):**
```php
'customer_name' => 'required|string|min:3|max:100',
'customer_phone' => 'required|string|regex:/^[0-9+\-\(\)\s]+$/|min:10|max:20',
'booking_date' => 'required|date|after_or_equal:today',
'booking_time' => 'required',
'customer_address' => 'nullable|string|max:500',
'notes' => 'nullable|string|max:500'
```

**Client-Side (HTML5):**
- Date picker dengan `min="{{ date('Y-m-d') }}"` (tidak bisa pilih tanggal lalu)
- Phone dengan regex validation
- Textarea dengan `maxlength` untuk notes dan address

bash
php artisan tinker
```

Kemudian jalankan:
```php
\App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password123')
]);
```

## 🔐 Autentikasi

Project ini menggunakan **dual authentication**:

### 1. Web Authentication (Session-based)
- Menggunakan Laravel built-in Auth
- Untuk akses dashboard web
- Protected routes menggunakan middleware `auth`
- Session disimpan di server

### 2. API Authentication (JWT-based)
- Menggunakan JWT (JSON Web Token)
- Untuk akses API endpoints
- Protected routes menggunakan middleware `auth:api`
- Stateless authentication

## 🔗 API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Authentication Endpoints (Public)

**Register:**
```http
POST /api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Login:**
```http
POST /api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

Response:
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "uuid": "550e8400-e29b-41d4-a716-446655440000",
            "name": "John Doe",
            "email": "john@example.com"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "type": "bearer",
        "expires_in": 3600
    }
}
```

### Protected Endpoints (Require JWT Token)

**Header untuk semua request protected:**
```http
Authorization: Bearer {your_jwt_token}
```

**Get Current User:**
```http
GET /api/me
```

**Refresh Token:**
```http
POST /api/refresh
```

**Logout:**
```http
POST /api/logout
```

**Categories CRUD:**
```http
GET    /api/categories           # List all categories
POST   /api/categories           # Create new category
GET    /api/categories/{uuid}    # Show specific category
PUT    /api/categories/{uuid}    # Update category
DELETE /api/categories/{uuid}    # Delete category
```

**Service Providers CRUD:**
```http
GET    /api/service-providers           # List all providers
POST   /api/service-providers           # Create new provider
GET    /api/service-providers/{uuid}    # Show specific provider
PUT    /api/service-providers/{uuid}    # Update provider
DELETE /api/service-providers/{uuid}    # Delete provider
```

## 🌐 Web Routes (Browser Access)

### Public Routes
- `GET /` - Landing page
- `GET /login` - Login form
- `POST /login` - Handle login
- `GET /register` - Register form
- `POST /register` - Handle registration

### Protected Routes (Require Login)
- `GET /dashboard` - Dashboard utama
  - **Admin**: Melihat 4 statistik card (Total Kategori, Total Provider, Total Booking, Pending Booking) + Tabel booking + Breakdown per kategori
  - **User**: Melihat 2 statistik card (Total Kategori, Total Provider) + Breakdown per kategori
- `POST /logout` - Logout

**Categories Management:**
- `GET /categories` - List semua kategori
- `GET /categories/create` - Form tambah kategori
- `POST /categories` - Simpan kategori baru
- `GET /categories/{uuid}` - Detail kategori
- `GET /categories/{uuid}/edit` - Form edit kategori
- `PUT /categories/{uuid}` - Update kategori
- `DELETE /categories/{uuid}` - Hapus kategori

**Service Providers Management:**
- `GET /service-providers` - List semua providers (dengan filter kategori)
- `GET /service-providers/create` - Form tambah provider (Admin only)
- `POST /service-providers` - Simpan provider baru (Admin only)
- `GET /service-providers/{uuid}` - Detail provider
- `GET /service-providers/{uuid}/edit` - Form edit provider (Admin only)
- `PUT /service-providers/{uuid}` - Update provider (Admin only)
- `DELETE /service-providers/{uuid}` - Hapus provider (Admin only)

**⚠️ Catatan Route Ordering:**
- Route `/create` dan `/{uuid}/edit` harus ditempatkan **SEBELUM** `/{uuid}`
- Ini mencegah error 404 karena "create" dianggap sebagai UUID

**Booking Management (User):**
- `GET /bookings/create` - Form pesan jasa (dengan filter kategori)
- `POST /bookings` - Simpan booking baru
- `GET /bookings/my-bookings` - Lihat booking sendiri
- `GET /bookings/{uuid}` - Detail booking
- `POST /bookings/{uuid}/cancel` - Batalkan booking (status pending only)

**Booking Management (Admin):**
- `GET /bookings` - Riwayat semua booking
- `GET /bookings/{uuid}` - Detail booking
- `POST /bookings/{uuid}/update-status` - Update status booking

## 🎨 Teknologi yang Digunakan

| Teknologi | Versi | Keterangan |
|-----------|-------|------------|
| **Laravel** | 12.x | Backend Framework |
| **PHP** | 8.2+ | Programming Language |
| **JWT Auth** | php-open-source-saver/jwt-auth | JSON Web Token Authentication |
| **MySQL** | 8.0+ | Database |
| **Tailwind CSS** | 4.0 | Frontend Styling |
| **Vite** | 7.0 | Asset Bundler |
| **Blade** | - | Template Engine |

## 📝 Implementasi Requirement

### ✅ 1. Backend Laravel 12
- [x] Menggunakan Laravel versi 12
- [x] Struktur MVC yang terorganisir
- [x] Migration untuk semua tabel
- [x] Seeders untuk data awal
- [x] Models dengan relationships

### ✅ 2. Autentikasi JWT
- [x] Package jwt-auth terinstall dan terkonfigurasi
- [x] Register endpoint (API)
- [x] Login endpoint (API)
- [x] Logout endpoint (API)
- [x] Token refresh endpoint
- [x] Protected routes dengan middleware auth:api

### ✅ 3. Frontend dengan Tailwind CSS
- [x] Tailwind CSS 4.0 terinstall
- [x] Integrasi dengan Vite
- [x] Laravel Blade templates
- [x] Responsive design
- [x] Form register & login
- [x] Dashboard interface
- [x] CRUD interfaces untuk Categories & Service Providers

### ✅ 4. Data Identifier (UUID)
- [x] Tabel Users memiliki kolom UUID
- [x] Tabel Categories memiliki kolom UUID  
- [x] Tabel Service Providers memiliki kolom UUID
- [x] UUID auto-generate di model boot()
- [x] UUID digunakan sebagai route parameter
- [x] getRouteKeyName() menggunakan 'uuid'

### ✅ 5. Fitur Register & Login
- [x] Form register dengan validasi
- [x] Form login dengan validasi
- [x] Password di-hash dengan bcrypt
- [x] Session management untuk web
- [x] JWT token untuk API
- [x] Logout functionality

### ✅ 6. Proteksi dengan JWT
- [x] Dashboard protected dengan middleware auth
- [x] CRUD routes protected dengan middleware auth
- [x] API routes protected dengan middleware auth:api
- [x] Redirect ke login jika belum auth

### ✅ 7. CRUD Minimal 2 Tabel Berelasi
- [x] **Categories Table** dengan CRUD lengkap
- [x] **Service Providers Table** dengan CRUD lengkap
- [x] **Bookings Table** dengan CRUD lengkap
- [x] Relasi: ServiceProvider belongsTo Category
- [x] Relasi: Category hasMany ServiceProviders
- [x] Relasi: Booking belongsTo User, ServiceProvider, Category
- [x] Cascade delete implementation
- [x] Form validation pada semua CRUD dengan regex pattern
- [x] Client-side validation (HTML5 attributes)
- [x] Server-side validation (Laravel rules)
- [x] Custom error messages untuk setiap field
- [x] Success/error messages
- [x] Pagination pada list view
- [x] Upload foto untuk service providers (max 2MB)
- [x] Filter kategori pada form booking
- [x] Filter kategori pada service providers list

### ✅ 8. Role System & Booking
- [x] Role admin & user dengan middleware protection
- [x] Admin dapat mengelola semua data (CRUD)
- [x] User hanya view untuk master data
- [x] User dapat membuat booking service provider
- [x] User dapat memilih service provider berdasarkan kategori
- [x] Admin dapat melihat log riwayat booking semua user
- [x] Admin dapat update status booking
- [x] User dapat membatalkan booking sendiri (status pending)
- [x] Dashboard menampilkan statistik booking (admin only)
- [x] Dashboard menampilkan jumlah service provider per kategori
- [x] Conditional UI berdasarkan role user

## 📚 Struktur Database & Relasi

```
users (Autentikasi)
├── id (PK)
├── uuid (Unique)
├── name
├── email (Unique)
├── password
├── role (admin/user)
└── [timestamps]
    │
    └── Has Many ──────────────────┐
                                   │
categories (Kategori Jasa)        │
├── id (PK)                        │
├── uuid (Unique)                  │
├── name                           │
├── description                    │
└── [timestamps]                   │
    │                              │
    ├── Has Many ──────┐           │
    │                  │           │
    └── Has Many ───┐  │           │
                    │  │           │
service_providers   │  │           │
├── id (PK)         │  │           │
├── uuid (Unique)   │  │           │
├── name            │  │           │
├── category_id (FK)┘  │           │
├── phone           Belongs To     │
├── address                        │
├── description                    │
├── photo                          │
└── [timestamps]                   │
    │                              │
    └── Has Many ──────┐           │
                       │           │
bookings               │           │
├── id (PK)            │           │
├── uuid (Unique)      │           │
├── user_id (FK) ──────┼───────────┘ Belongs To
├── service_provider_id (FK) ──────┘ Belongs To
├── category_id (FK) ──────────────┘ Belongs To
├── booking_date
├── booking_time
├── notes
├── customer_name
├── customer_phone
├── customer_address
├── status (pending/confirmed/completed/cancelled)
└── [timestamps]
```

**Penjelasan Relasi:**
1. **Users → Bookings**: Satu user bisa punya banyak booking (One-to-Many)
2. **Categories → Service Providers**: Satu kategori bisa punya banyak provider (One-to-Many)
3. **Categories → Bookings**: Satu kategori bisa ada di banyak booking (One-to-Many)
4. **Service Providers → Bookings**: Satu provider bisa punya banyak booking (One-to-Many)

## 🧪 Testing

### Manual Testing Web
1. **Login sebagai Admin** (admin@admin.com / admin123):
   - Akses dashboard (lihat 4 statistik card + booking table + kategori breakdown)
   - Test CRUD Categories (Tambah, Edit, Lihat, Hapus)
   - Test CRUD Service Providers (Tambah dengan foto, Edit, Lihat, Hapus)
   - Test validasi form (telepon hanya angka, email valid, field required)
   - Filter service providers berdasarkan kategori
   - Lihat riwayat booking di menu "Riwayat Booking"
   - Update status booking dari pending ke confirmed/completed
   
2. **Login sebagai User** (user@test.com / user123):
   - Akses dashboard (lihat 2 statistik card + kategori breakdown)
   - Browse categories dan service providers (view only)
   - Filter service providers berdasarkan kategori
   - Pilih kategori di menu "Pesan Jasa"
   - Pilih service provider dan isi form booking
   - Test validasi form booking (nomor telepon, tanggal)
   - Lihat booking sendiri di "Booking Saya"
   - Batalkan booking dengan status pending
   - Test logout

3. **Test Register User Baru**:
   - Register akun baru
   - Login dengan akun baru (role otomatis "user")
   - Test fitur booking

4. **Test Validasi**:
   - Coba input telepon dengan huruf (harus ditolak)
   - Coba input email invalid (harus ditolak)
   - Coba upload foto > 2MB (harus ditolak)
   - Coba pilih tanggal booking di masa lalu (harus ditolak)
   - Test field required dengan submit kosong

### Manual Testing API (Postman/Insomnia)
1. POST /api/register - Register user
2. POST /api/login - Dapatkan JWT token
3. Gunakan token di header untuk endpoint protected
4. Test semua CRUD endpoints
5. POST /api/refresh - Refresh token
6. POST /api/logout - Logout

## 🔧 Troubleshooting

### Error: "Please publish the [jwt] configuration file"
```bash
php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
```

### Error: "Storage not linked"
```bash
php artisan storage:link
```

### Error: "No application encryption key"
```bash
php artisan key:generate
```

### Error: "Table doesn't exist"
```bash
php artisan migrate:fresh --seed
```

### Tailwind CSS tidak bekerja
```bash
npm install
npm run build
```

### Foto tidak muncul setelah upload
- Pastikan sudah menjalankan `php artisan storage:link`
- Cek folder `storage/app/public/service-providers` sudah ada
- Cek permission folder storage (chmod 775)

### Validasi form tidak bekerja
- Pastikan JavaScript enabled di browser
- Clear browser cache
- Cek console browser untuk error
- Pattern regex di HTML5 harus match dengan Laravel validation

### Error 404 saat akses route /create atau /edit
- Pastikan route ordering benar di `routes/web.php`
- Route specific (`/create`, `/edit`) harus sebelum wildcard (`/{uuid}`)

### Email column error pada service providers
- Pastikan migration email sudah dijalankan
- Run: `php artisan migrate`
- Atau: `php artisan migrate:fresh --seed` (reset database)

## 📧 Kontak & Support

Jika ada pertanyaan atau issue, silakan hubungi:
- Email: admin@koneksijasa.com
- GitHub Issues: [Create Issue]

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**Dibuat dengan ❤️ menggunakan Laravel 12, JWT, dan Tailwind CSS**
            "GET /api/service-providers": "Get all with pagination & search",
            "GET /api/service-providers/{id}": "Get by ID",
            "POST /api/service-providers": "Create new",
            "PUT /api/service-providers/{id}": "Update",
            "DELETE /api/service-providers/{id}": "Delete"
        }
    }
}
```

### 2. Service Providers Endpoints

#### a. Get All Service Providers (Dengan Pagination & Search) 📋
```
GET /api/service-providers
```

**Query Parameters:**
- `limit` (integer, 1-100, default: 10) - Jumlah data per halaman
- `page` (integer, default: 1) - Halaman yang diminta
- `search` (string) - Pencarian di nama, deskripsi, atau alamat
- `category` (integer) - Filter berdasarkan kategori ID

**Example Request:**
```bash
GET /api/service-providers?limit=10&page=1&search=carwash&category=1
```

**Response Format:**
```json
{
    "success": true,
    "message": "Service providers retrieved successfully",
    "data": {
        "providers": [
            {
                "id": 1,
                "uuid": "123e4567-e89b-12d3-a456-426614174000",
                "name": "AutoClean Car Wash",
                "category_id": 1,
                "phone": "08123456789",
                "email": "autoclean@example.com",
                "address": "Jl. Sudirman No. 123",
                "description": "Professional car wash service",
                "photo": "/storage/service-providers/photo.jpg",
                "created_at": "2025-11-25T15:37:16.000000Z",
                "updated_at": "2025-11-25T15:37:16.000000Z",
                "category": {
                    "id": 1,
                    "uuid": "456e7890-e89b-12d3-a456-426614174001",
                    "name": "Cuci Mobil",
                    "description": "Jasa pencucian mobil, detailing, dan perawatan kendaraan"
                }
            }
        ],
        "pagination": {
            "current_page": 1,
            "total_pages": 5,
            "per_page": 10,
            "total_items": 45,
            "has_next_page": true,
            "has_prev_page": false
        }
    }
}
```

#### b. Get Service Provider by ID 🔍
```
GET /api/service-providers/{id}
```

**Example Request:**
```bash
GET /api/service-providers/1
```

#### c. Create Service Provider ➕
```
POST /api/service-providers
```

**Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
    "name": "AutoClean Car Wash",
    "category_id": 1,
    "phone": "08123456789",
    "email": "autoclean@example.com",
    "address": "Jl. Sudirman No. 123, Jakarta Selatan",
    "description": "Jasa cuci mobil profesional dengan peralatan modern"
}
```

**Validasi:**
- `name`: required, string, 3-100 karakter
- `category_id`: required, harus exist di tabel categories
- `phone`: nullable, hanya angka/+/-/()/ spasi, 10-20 karakter, regex `/^[0-9+\-\(\)\s]+$/`
- `email`: nullable, format email valid, max 255 karakter
- `address`: nullable, max 500 karakter
- `description`: nullable, max 1000 karakter

#### d. Update Service Provider ✏️
```
PUT /api/service-providers/{id}
```

**Request Body (semua field optional):**
```json
{
    "name": "AutoClean Car Wash Updated",
    "phone": "081234567890",
    "email": "newemail@example.com"
}
```

**Validasi:**
- Sama seperti Create, namun semua field optional

#### e. Delete Service Provider 🗑️
```
DELETE /api/service-providers/{id}
```

### 3. Categories Endpoints

#### a. Get All Categories
```
GET /api/categories
```

**Response Format:**
```json
{
    "success": true,
    "message": "Categories retrieved successfully",
    "data": [
        {
            "id": 1,
            "name": "Cuci Mobil",
            "description": "Jasa pencucian mobil, detailing, dan perawatan kendaraan"
        },
        {
            "id": 2,
            "name": "Service AC",
            "description": "Jasa perbaikan, maintenance, dan instalasi AC mobil dan ruangan"
        }
    ]
}
```

## ⚠️ Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation errors",
    "errors": {
        "name": ["The name field is required."],
        "category_id": ["The category id must exist in categories table."],
        "phone": ["Nomor telepon hanya boleh berisi angka, +, -, (, ), dan spasi."],
        "email": ["Format email tidak valid."]
    }
}
```

**Contoh Error Validasi Telepon:**
```json
{
    "errors": {
        "phone": [
            "Nomor telepon hanya boleh berisi angka, +, -, (, ), dan spasi.",
            "Nomor telepon minimal 10 karakter."
        ]
    }
}
```

### Not Found Error (404)
```json
{
    "success": false,
    "message": "Service provider not found"
}
```

### Internal Server Error (500)
```json
{
    "success": false,
    "message": "Internal server error",
    "error": "Database connection failed"
}
```

## 🛠️ Setup & Installation

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 5.7+ or MariaDB 10.3+
- Laravel 11

### Step 1: Install Dependencies
```bash
cd demo_web
composer install
```

### Step 2: Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

Configure database di `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=koneksi_jasa
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Run Migration & Seeder
```bash
php artisan migrate:fresh --seed
```

### Step 4: Start Development Server
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

API akan berjalan di: `http://localhost:8000/api`

## 📮 Postman Collection

Import file `Koneksi-Jasa-API.postman_collection.json` ke Postman untuk testing semua endpoints dengan mudah.

### Cara Import:
1. Buka Postman
2. Klik "Import" → "Upload Files"
3. Pilih file `Koneksi-Jasa-API.postman_collection.json`
4. Semua endpoint akan tersedia dengan contoh request

## 🧪 Testing dengan cURL

### 1. Test Root Endpoint
```bash
curl -X GET "http://localhost:8000/api" -H "Accept: application/json"
```

### 2. Get All Service Providers
```bash
curl -X GET "http://localhost:8000/api/service-providers?limit=5&page=1&search=cuci&category=1" -H "Accept: application/json"
```

### 3. Create Service Provider
```bash
curl -X POST "http://localhost:8000/api/service-providers" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test Car Wash",
    "category_id": 1,
    "phone": "08123456789",
    "email": "test@example.com",
    "address": "Jl. Test No. 123",
    "description": "Testing service provider"
  }'
```

### 4. Test Validation Error (Invalid Phone)
```bash
curl -X POST "http://localhost:8000/api/service-providers" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test Car Wash",
    "category_id": 1,
    "phone": "abcd123",
    "email": "test@example.com"
  }'
```

**Expected Response:**
```json
{
  "success": false,
  "message": "Validation errors",
  "errors": {
    "phone": [
      "Nomor telepon hanya boleh berisi angka, +, -, (, ), dan spasi.",
      "Nomor telepon minimal 10 karakter."
    ]
  }
}
```

## 🔧 Fitur Tambahan yang Dapat Dikembangkan

1. **✅ COMPLETED**: Booking System dengan relasi user, service provider, dan category
2. **✅ COMPLETED**: Role-based Access Control (Admin & User)
3. **✅ COMPLETED**: Upload foto service provider dengan validasi
4. **✅ COMPLETED**: Dashboard dengan statistik booking (admin only)
5. **✅ COMPLETED**: Filter service providers berdasarkan kategori
6. **✅ COMPLETED**: Validasi form (phone regex, email, length constraints)
7. **✅ COMPLETED**: Dashboard breakdown per kategori dengan gradient cards
8. **⭐ Review & Rating System**: Tabel reviews untuk user feedback
9. **🔔 Notification System**: Notifikasi untuk status booking
10. **📧 Email Notification**: Email saat booking dibuat/diupdate
11. **💳 Payment Integration**: Integrasi dengan payment gateway
12. **📱 Mobile API**: Optimasi API untuk mobile app
4. **📸 Image Upload**: Media library untuk foto portfolio
5. **🗺️ Location Search**: Integrasi dengan Google Maps API
6. **📧 Notification System**: Email/SMS notification untuk booking
7. **💳 Payment Gateway**: Integrasi dengan payment provider
8. **👨‍💻 Admin Dashboard**: Laravel Nova atau Filament untuk admin panel

## 🤝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/NewFeature`)
3. Commit changes (`git commit -am 'Add new feature'`)
4. Push to branch (`git push origin feature/NewFeature`)
5. Create Pull Request

## 📄 License

This project is licensed under the MIT License.

---

**Happy Coding! 🚀✨**
