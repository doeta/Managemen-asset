# 📦 Managemen Asset

> Aplikasi manajemen aset berbasis CodeIgniter 4 untuk pencatatan aset, barang habis pakai, pemakaian, dan riwayat kepemilikan di lingkungan organisasi/pemerintahan.

## ✨ Fitur Utama

- 🏷️ **Kelola Asset & Kategori** - Manajemen asset, sub-kategori, dan barang dengan kode unik/serial
- 📊 **Status Tracking** - Tersedia, Terpakai, Habis Terpakai
- 👥 **Riwayat Kepemilikan** - Pelacakan perubahan pemilik per barang
- 📉 **Auto Stock Reduction** - Pengurangan stok otomatis untuk barang habis pakai
- 📈 **Laporan & Statistik** - Dashboard dan laporan per kategori
- 🔍 **Filter & Search** - Pencarian dan filter data yang fleksibel

## 🛠 Tech Stack

- **Framework**: CodeIgniter 4
- **Database**: MySQL / MariaDB
- **Frontend**: Bootstrap 5, jQuery, DataTables
- **Dependencies**: Composer

## 📋 Requirements

- PHP 7.4+ (recommend PHP 8.1+)
- MySQL 5.7+ / MariaDB 10.3+
- Composer
- Extensions: `mbstring`, `intl`, `json`, `mysqlnd`

## 🚀 Quick Start

### 1. Clone & Install

```bash
git clone https://github.com/doeta/Managemen-asset.git
cd Managemen-asset
composer install
```

### 2. Database Setup

```bash
# Copy environment file
cp .env.example .env

# Edit database config in .env
# database.default.hostname = localhost
# database.default.database = dbasset
# database.default.username = root
# database.default.password =
```

#### Database Migration & Seeding

```bash
# Jalankan migration untuk membuat struktur tabel
php spark migrate

# Jalankan seeder untuk data sample (opsional - hanya untuk testing/development)
php spark db:seed DatabaseSeeder

# Atau jalankan seeder individual:
php spark db:seed UsersSeeder     # User admin default
php spark db:seed KategoriSeeder  # Data kategori sample (8 kategori contoh)
php spark db:seed LokasiSeeder    # Data lokasi sample (8 lokasi contoh)
```

> **⚠️ Catatan Penting:**
>
> - Seeder hanya berisi **data contoh/sample** untuk keperluan development dan testing
> - **JANGAN** jalankan seeder di environment production
> - Data seeder dapat dihapus/dimodifikasi sesuai kebutuhan organisasi
> - Untuk production, input data master melalui aplikasi web

#### Database Rollback (Development)

```bash
# Rollback migration terakhir
php spark migrate:rollback

# Rollback ke versi tertentu
php spark migrate:rollback -b 2025-04-09-070333

# Reset semua migration
php spark migrate:reset
```

### 3. Run Application

```bash
# Development server
php spark serve

# Open browser: http://localhost:8080
```

### 4. Default Login

```
Username: admin
Password: admin123
```

**Alternative untuk Laragon/XAMPP**: Tempatkan di folder web server dan setup virtual host.

## 📁 Project Structure

```
├── app/
│   ├── Controllers/     # Application controllers
│   ├── Models/         # Database models
│   ├── Views/          # View templates
│   └── Config/         # Configuration files
├── public/             # Web root (index.php, assets)
├── writable/           # Cache, logs, uploads
└── vendor/             # Composer dependencies
```

## � Team Development Setup

### Untuk Developer Baru:

1. **Clone Repository**

```bash
git clone https://github.com/doeta/Managemen-asset.git
cd Managemen-asset
composer install
```

2. **Environment Setup**

```bash
# Copy dan edit file environment
cp .env.example .env
# Edit database credentials sesuai environment lokal Anda
```

3. **Database Setup**

```bash
# Buat database baru di MySQL/MariaDB
CREATE DATABASE dbasset;

# Jalankan migration untuk struktur tabel
php spark migrate

# Jalankan seeder untuk data sample (opsional - hanya untuk testing)
php spark db:seed DatabaseSeeder
```

> **💡 Tips untuk Development:**
>
> - Seeder berisi data contoh kategori (Elektronik, Komputer, Furniture, dll) dan lokasi sample
> - Data ini membantu development dan testing tanpa perlu input manual
> - Hapus/ganti data seeder sesuai kebutuhan organisasi Anda

4. **Test Application**

```bash
php spark serve
# Akses: http://localhost:8080
# Login: admin / admin123
```

### Database Schema:

- **kategori** - Master kategori asset
- **sub_kategori** - Sub kategori dari kategori utama
- **lokasi** - Master lokasi penyimpanan
- **asset** - Data asset utama
- **barang** - Detail barang per asset
- **users** - User management
- **pemakaian** - Riwayat pemakaian barang
- **riwayat_asset** - History perubahan asset

### Important Notes:

⚠️ **File `.env` TIDAK di-push ke repository** (berisi credential database)  
⚠️ **File database tidak di-upload** - gunakan migration & seeder  
⚠️ **Selalu jalankan migration** setelah pull update dari repository

## �💡 Usage Guide

1. **Dashboard** - Overview statistik dan status aset
2. **Data Assets** - Kelola sub-kategori dan lihat stok
3. **Detail Barang** - Info lengkap dan riwayat kepemilikan
4. **Pemakaian** - Input pemakaian akan auto-reduce stok (barang habis pakai)

## 👨‍💻 Developer

**Nama**: Duta Adi Pamungkas  
**NIM**: 24060123140174  
**Program Studi**: S1 Informatika  
**Universitas**: Universitas Diponegoro

## �📄 License

Distributed under the MIT License. See `LICENSE` for more information.

---

<p align="center">Made with ❤️ for better asset management</p>
