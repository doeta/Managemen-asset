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

### 3. Run Application

```bash
# Development server
php spark serve

# Open browser: http://localhost:8080
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

## 💡 Usage Guide

1. **Dashboard** - Overview statistik dan status aset
2. **Data Assets** - Kelola sub-kategori dan lihat stok
3. **Detail Barang** - Info lengkap dan riwayat kepemilikan
4. **Pemakaian** - Input pemakaian akan auto-reduce stok (barang habis pakai)

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

---

<p align="center">Made with ❤️ for better asset management</p>
