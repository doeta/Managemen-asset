<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">
  <!-- Brand Logo - TETAP DI ATAS -->
  <a href="<?= base_url('/dashboard') ?>" class="brand-link p-0" style="background: white;">
    <div class="text-center py-3">
      <img src="<?= base_url('assets/img/kebumen.png') ?>" 
          alt="Logo Kominfo"
          style="width: 100px; max-width: 80%; height: auto;">
      <div class="text-primary font-weight-bold" style="font-size: 1.3rem; margin-top: 8px;">
        Asset Kominfo
      </div>
    </div>
  </a>

  <!-- Sidebar Content - BISA SCROLL -->
  <div class="sidebar">
    <div class="sidebar-menu">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <!-- Dashboard -->
          <li class="nav-item">
            <a href="<?= base_url('/dashboard') ?>" class="nav-link<?= ($menu == 'dashboard') ? ' active': '' ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <!-- Data Assets -->
          <li class="nav-item">
            <a href="<?= base_url('/dataassets') ?>" class="nav-link<?= ($menu == 'dataassets') ? ' active': '' ?>">
              <i class="nav-icon fas fa-boxes"></i>
              <p>Data Asset</p>
            </a>
          </li>

          <!-- Kendaraan Dinas -->
          <li class="nav-item<?= ($menu == 'mobil' || $menu == 'motor') ? ' menu-open' : '' ?>">
            <a href="#" class="nav-link<?= ($menu == 'mobil' || $menu == 'motor') ? ' active' : '' ?>">
              <i class="nav-icon fas fa-car"></i>
              <p>
                Kendaraan Dinas
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview pl-3">
              <li class="nav-item">
                <a href="<?= base_url('kendaraan/mobil') ?>" class="nav-link<?= ($menu == 'mobil') ? ' active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mobil</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('kendaraan/motor') ?>" class="nav-link<?= ($menu == 'motor') ? ' active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Motor</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Data Master -->
          <li class="nav-item<?= in_array($menu, ['kategori', 'subkategori', 'asset', 'lokasi', 'pengguna', 'kendaraan']) ? ' menu-open' : '' ?>">
            <a href="#" class="nav-link<?= in_array($menu, ['kategori', 'subkategori', 'asset', 'lokasi', 'pengguna', 'kendaraan']) ? ' active' : '' ?>">
              <i class="nav-icon fas fa-database"></i>
              <p>Data Master<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview pl-3">
              <li class="nav-item">
                <a href="<?= base_url('/kategori') ?>" class="nav-link<?= ($menu == 'kategori') ? ' active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kategori Asset</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('/subkategori') ?>" class="nav-link<?= ($menu == 'subkategori') ? ' active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sub Kategori Asset</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('/asset') ?>" class="nav-link<?= ($menu == 'asset') ? ' active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Pengadaan Asset</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('/lokasi') ?>" class="nav-link<?= ($menu == 'lokasi') ? ' active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Lokasi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('/pengguna') ?>" class="nav-link<?= ($menu == 'pengguna') ? ' active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pengguna</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('/kendaraan') ?>" class="nav-link<?= ($menu == 'kendaraan') ? ' active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kendaraan</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Pembelian -->
          <li class="nav-item">
            <a href="<?= base_url('riwayatpembelian') ?>" class="nav-link<?= ($menu == 'riwayatpembelian') ? ' active': '' ?>">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>Pembelian</p>
            </a>
          </li>

          <!-- Pemakaian -->
          <li class="nav-item">
            <a href="<?= base_url('pemakaian') ?>" class="nav-link<?= ($menu == 'pemakaian') ? ' active': '' ?>">
              <i class="nav-icon fas fa-tasks"></i>
              <p>Pemakaian</p>
            </a>
          </li>

          <!-- Laporan -->
          <li class="nav-item<?= in_array($menu, ['laporan_dataasset', 'laporan_kendaraan', 'laporan_pembelian', 'laporan_pengadaan', 'laporan_pemakaian']) ? ' menu-open' : '' ?>">
            <a href="#" class="nav-link<?= in_array($menu, ['laporan_dataasset', 'laporan_kendaraan', 'laporan_pembelian', 'laporan_pengadaan', 'laporan_pemakaian']) ? ' active' : '' ?>">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>
                Laporan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview pl-3">
              <li class="nav-item">
                <a href="<?= base_url('laporan/dataasset') ?>" class="nav-link<?= ($menu == 'laporan_dataasset') ? ' active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Asset</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('laporan/kendaraan') ?>" class="nav-link<?= ($menu == 'laporan_kendaraan') ? ' active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kendaraan Dinas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('laporan/pembelian') ?>" class="nav-link<?= ($menu == 'laporan_pembelian') ? ' active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pembelian</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('laporan/pemakaian') ?>" class="nav-link<?= ($menu == 'laporan_pemakaian') ? ' active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pemakaian</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Management User (hanya untuk admin) -->
          <?php if (session('role') == 'admin'): ?>
            <li class="nav-item">
              <a href="<?= base_url('auth/users') ?>" class="nav-link<?= ($menu == 'users') ? ' active': '' ?>">
                <i class="nav-icon fas fa-users"></i>
                <p>Manajemen User</p>
              </a>
            </li>
          <?php endif; ?>

          <!-- Logout -->
          <li class="nav-item">
            <a href="<?= base_url('/auth/logout') ?>" class="nav-link bg-danger text-white">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>

        </ul>
      </nav>
    </div>
  </div>
</aside>

<style>
/* Main sidebar container */
.main-sidebar {
  display: flex;
  flex-direction: column;
  height: 100vh;
  overflow: hidden;
}

/* Brand logo - tetap di atas */
.brand-link {
  flex-shrink: 0; /* Tidak mengecil */
  position: sticky;
  top: 0;
  z-index: 10;
  border-bottom: 1px solid #dee2e6;
}

/* Sidebar content - bisa scroll */
.sidebar {
  flex: 1; /* Mengambil sisa ruang yang tersedia */
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.sidebar-menu {
  flex: 1;
  overflow-y: auto; /* Aktifkan scroll vertikal */
  overflow-x: hidden; /* Nonaktifkan scroll horizontal */
  padding-bottom: 1rem; /* Tambahkan padding bawah */
}

/* Scrollbar styling - kursor penanda tipis */
.sidebar-menu {
  scrollbar-width: thin; /* Firefox: scrollbar tipis */
  scrollbar-color: #007bff rgba(0,0,0,0.1); /* Firefox: biru primary dengan track transparan */
}

/* Chrome, Safari, Edge - Scrollbar tipis dan elegan */
.sidebar-menu::-webkit-scrollbar {
  width: 4px; /* Lebih tipis dari sebelumnya */
}

.sidebar-menu::-webkit-scrollbar-track {
  background: rgba(0,0,0,0.05); /* Track dengan warna abu-abu sangat tipis */
  border-radius: 2px;
}

.sidebar-menu::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, #007bff, #0056b3); /* Gradient biru sesuai tema */
  border-radius: 2px;
  transition: all 0.3s ease;
}

.sidebar-menu::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(180deg, #0056b3, #003d82); /* Warna lebih gelap saat hover */
  width: 6px; /* Sedikit melebar saat hover untuk feedback visual */
}

/* Styling tambahan untuk menu */
.nav-sidebar .nav-item .nav-link {
  border-radius: 0.25rem;
  margin: 0.1rem 0.5rem;
}

/* Hover effect yang smooth */
.nav-sidebar .nav-item .nav-link:hover {
  background-color: rgba(0,123,255,0.1);
  transition: background-color 0.3s ease;
}

/* Active menu styling */
.nav-sidebar .nav-item .nav-link.active {
  background-color: #007bff;
  color: white;
}
</style>