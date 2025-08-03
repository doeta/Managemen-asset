<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url('/dashboard') ?>" class="brand-link text-center">
    <span class="brand-text font-weight-light">Asset Kominfo</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
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

        <!-- Data Asset -->
<?php /*<li class="nav-item<?= ($menu == 'barangmodal' || $menu == 'baranghabispakai') ? ' menu-open': '' ?>">
  <a href="#" class="nav-link<?= ($menu == 'barangmodal' || $menu == 'baranghabispakai') ? ' active': '' ?>">
    <i class="nav-icon fas fa-boxes"></i>
    <p>Data Asset<i class="right fas fa-angle-left"></i></p>
  </a>
  <ul class="nav nav-treeview pl-3">
    <li class="nav-item">
      <a href="<?= base_url('/barangmodal') ?>" class="nav-link<?= ($menu == 'barangmodal') ? ' active': '' ?>">
        <i class="far fa-circle nav-icon"></i>
        <p>Barang Modal</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= base_url('/baranghabispakai') ?>" class="nav-link<?= ($menu == 'baranghabispakai') ? ' active': '' ?>">
        <i class="far fa-circle nav-icon"></i>
        <p>Barang Habis Pakai</p>
      </a>
    </li>
  </ul>
</li>*/ ?>


<!-- Kendaraan Dinas -->
<li class="nav-item<?= ($menu == 'mobil' || $menu == 'motor') ? ' menu-open' : '' ?>">
  <a href="#" class="nav-link<?= ($menu == 'mobil' || $menu == 'motor') ? ' active' : '' ?>">
    <i class="nav-icon fas fa-car"></i> <!-- Gunakan ikon yang relevan, bukan fa-boxes -->
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

  <li class="nav-item">
  <a href="<?= base_url('riwayatpembelian') ?>" class="nav-link<?= ($menu == 'riwayatpembelian') ? ' active': '' ?>">
    <i class="nav-icon fas fa-shopping-cart"></i>
    <p>Pembelian</p>
  </a>
</li>

  <li class="nav-item">
            <a href="<?= base_url('pemakaian') ?>" class="nav-link<?= ($menu == 'pemakaian') ? ' active': '' ?>">
              <i class="nav-icon 	fas fa-tasks"></i>
              <p>Pemakaian</p>
            </a>
          </li>

<!-- Laporan Gabungan -->

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
    <!-- Tambahkan sub-menu lain sesuai kebutuhan -->
  </ul>
</li>



        <?php if (session('role') == 'admin'): ?>
          <li class="nav-item">
            <a href="<?= base_url('auth/users') ?>" class="nav-link<?= ($menu == 'users') ? ' active': '' ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>Manajemen User</p>
            </a>
          </li>
        <?php endif; ?>

        <li class="nav-item">
          <a href="<?= base_url('/auth/logout') ?>" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Logout</p>
          </a>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>