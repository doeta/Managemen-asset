<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Login & Auth (Tanpa filter)
$routes->get('/login', 'AuthController::login');   
$routes->post('/auth/authenticate', 'AuthController::authenticate');
$routes->get('/auth/logout', 'AuthController::logout');

// Group semua route lain dengan filter auth
$routes->group('', ['filter' => 'auth'], function($routes) {

    // Dashboard & Home
    $routes->get('/', 'Home::index');
    $routes->get('/dashboard', 'DasboardController::index');

    // -----------------------------
    //         DATA MASTER
    // -----------------------------
    // Lokasi
    $routes->get('/lokasi', 'LokasiController::lokasi'); 
    $routes->get('/lokasi/create', 'LokasiController::create');
    $routes->post('/lokasi/store', 'LokasiController::store');
    $routes->get('/lokasi/edit/(:num)', 'LokasiController::edit/$1');
    $routes->post('/lokasi/update/(:num)', 'LokasiController::update/$1');
    $routes->delete('/lokasi/delete/(:num)', 'LokasiController::delete/$1');    

    // Pengguna
    $routes->get('/pengguna', 'PenggunaController::pengguna');  
    $routes->get('/pengguna/create', 'PenggunaController::create');
    $routes->post('/pengguna/store', 'PenggunaController::store');
    $routes->get('/pengguna/edit/(:num)', 'PenggunaController::edit/$1');
    $routes->post('/pengguna/update/(:num)', 'PenggunaController::update/$1');
    $routes->delete('/pengguna/delete/(:num)', 'PenggunaController::delete/$1');

    // -----------------------------
    //           ASSET
    // -----------------------------
    $routes->group('asset', function($routes) {
        $routes->get('/', 'AssetController::index');
        $routes->get('create', 'AssetController::create');
        $routes->post('store', 'AssetController::store');
        $routes->get('edit/(:num)', 'AssetController::edit/$1');
        $routes->post('update/(:num)', 'AssetController::update/$1');
        $routes->delete('delete/(:num)', 'AssetController::delete/$1');
        $routes->get('show/(:num)', 'AssetController::show/$1');
        $routes->get('riwayat/(:num)', 'AssetController::riwayat/$1');
        $routes->get('riwayatasset/(:num)', 'AssetController::riwayatAsset/$1');

    });


    // -----------------------------
    //           Data Assets
    // -----------------------------

    $routes->get('/dataassets', 'DataAssetsController::index');
    $routes->get('/dataassets/detailSubKategori/(:segment)', 'DataAssetsController::detailSubKategori/$1');
    $routes->get('/dataassets/detail/(:segment)', 'DataAssetsController::detail/$1');
    $routes->get('/dataassets/edit/(:num)', 'DataAssetsController::edit/$1');
    $routes->post('/dataassets/update/(:num)', 'DataAssetsController::update/$1');
    $routes->delete('/dataassets/delete/(:num)', 'DataAssetsController::delete/$1');



    // -----------------------------
    //         KENDARAAN
    // -----------------------------
    $routes->group('kendaraan', function($routes) {
        $routes->get('/', 'KendaraanController::kendaraan');
        $routes->get('create', 'KendaraanController::create');
        $routes->post('store', 'KendaraanController::store');
        $routes->get('edit/(:num)', 'KendaraanController::edit/$1');
        $routes->post('update/(:num)', 'KendaraanController::update/$1');
        $routes->delete('delete/(:num)', 'KendaraanController::delete/$1');
        $routes->get('show/(:num)', 'KendaraanController::show/$1');

        // Mobil
        $routes->get('mobil', 'KendaraanController::mobil');
        $routes->get('editmobil/(:num)', 'KendaraanController::editmobil/$1');
        $routes->post('mobil/riwayat/tambah/(:num)', 'KendaraanController::tambahRiwayatMobil/$1');
        $routes->get('mobil/delete/(:num)', 'KendaraanController::delete/$1');
        $routes->get('mobildetail/(:num)', 'KendaraanController::mobildetail/$1');
        $routes->get('updateriwayatmobil/(:num)', 'KendaraanController::updateriwayatmobil/$1');
        $routes->get('riwayatmobil/(:num)', 'KendaraanController::riwayatmobil/$1');

        // Motor
        $routes->get('motor', 'KendaraanController::motor');
        $routes->get('editmotor/(:num)', 'KendaraanController::editmotor/$1');
        $routes->post('motor/riwayat/tambah/(:num)', 'KendaraanController::tambahRiwayatMotor/$1');
        $routes->get('riwayatmotor/(:num)', 'KendaraanController::riwayatmotor/$1');
    });

    // -----------------------------
    //        KATEGORI / SUBKATEGORI
    // -----------------------------
    $routes->group('kategori', function($routes) {
        $routes->get('/', 'KategoriController::index');
        $routes->get('create', 'KategoriController::create');
        $routes->post('store', 'KategoriController::store');
        $routes->get('edit/(:num)', 'KategoriController::edit/$1');
        $routes->post('update/(:num)', 'KategoriController::update/$1');
        $routes->get('show/(:num)', 'KategoriController::show/$1');
        $routes->post('delete/(:num)', 'KategoriController::delete/$1');
        $routes->delete('delete/(:num)', 'KategoriController::delete/$1');
    });

    $routes->group('subkategori', function($routes) {
        $routes->get('/', 'SubKategoriController::index');
        $routes->get('create', 'SubKategoriController::create');
        $routes->post('store', 'SubKategoriController::store');
        $routes->get('edit/(:num)', 'SubKategoriController::edit/$1');
        $routes->post('update/(:num)', 'SubKategoriController::update/$1');
        $routes->get('show/(:num)', 'SubKategoriController::show/$1');
        $routes->post('delete/(:num)', 'SubKategoriController::delete/$1');
        $routes->delete('delete/(:num)', 'SubKategoriController::delete/$1');
    });

    // -----------------------------
    //  BARANG MODAL & HABIS PAKAI
    // -----------------------------
    $routes->post('/barang/update/(:num)', 'BarangController::update/$1');

    // Barang Modal
    $routes->get('/barangmodal', 'BarangController::barangModal');
    $routes->get('/detailbarangmodal/(:segment)', 'BarangController::detailbarangmodal/$1');
    $routes->get('/barangmodal/edit/(:num)', 'BarangController::editBarangModal/$1');
    $routes->post('/barangmodal/update/(:num)', 'BarangController::updateBarangModal/$1');
    $routes->delete('/barangmodal/delete/(:num)', 'BarangController::deleteBarangModal/$1');
    $routes->get('/barangmodal/riwayat/(:num)', 'BarangController::riwayatBarangModal/$1');
    $routes->get('/barangmodal/search', 'BarangController::searchBarangModal');

    // Barang Habis Pakai
    $routes->get('/baranghabispakai', 'BarangController::baranghabispakai');
    $routes->get('/detailbaranghabispakai/(:segment)', 'BarangController::detailbaranghabispakai/$1');
    $routes->get('/baranghabispakai/edit/(:num)', 'BarangController::editBarangHabisPakai/$1');
    $routes->post('/baranghabispakai/update/(:num)', 'BarangController::updateBarangHabisPakai/$1');
    $routes->delete('/baranghabispakai/delete/(:num)', 'BarangController::deleteBarangHabisPakai/$1');
    $routes->get('/baranghabispakai/riwayat/(:num)', 'BarangController::riwayatBarangHabisPakai/$1');
    $routes->get('/baranghabispakai/search', 'BarangController::searchBarangHabisPakai');

    // -----------------------------
    //           PEMBELIAN
    // -----------------------------
    $routes->get('/pembelian', 'PembelianController::pembelian');
    $routes->get('/pembelian/create/(:num)', 'PembelianController::create/$1'); // untuk asset tertentu
    $routes->post('/pembelian/store/(:num)', 'PembelianController::store/$1');
    $routes->post('/pembelian/simpan', 'PembelianController::store');
    $routes->post('/pembelian/delete/(:num)', 'PembelianController::delete/$1');
    $routes->get('pembelian/riwayat', 'PembelianController::riwayatPembelian');

    // Riwayat Pembelian (optional)
    $routes->get('/riwayatpembelian', 'PembelianController::riwayatPembelian');
    $routes->get('/riwayatpembelian/(:num)', 'PembelianController::riwayatPembelian/$1');
    $routes->get('/riwayatbarang', 'PembelianController::riwayatBarang');
    $routes->get('/riwayatbarang/(:num)', 'PembelianController::riwayatBarang/$1');
   

    // -----------------------------
    //           PEMAKAIAN
    // -----------------------------
    $routes->get('/pemakaian', 'PemakaianController::pemakaian');
    $routes->get('/pemakaian/create', 'PemakaianController::create'); 
    $routes->post('/pemakaian/simpan', 'PemakaianController::simpan');

    // -----------------------------
    //       USER MANAGEMENT
    // -----------------------------
    $routes->get('/auth/users', 'AuthController::listUsers');
    $routes->get('/auth/users/create', 'AuthController::createUser');
    $routes->post('/auth/users/store', 'AuthController::storeUser');
    $routes->delete('/auth/users/delete/(:num)', 'AuthController::deleteUser/$1');

    // -----------------------------
    //       Laporan
    // -----------------------------
    $routes->group('laporan', function($routes) {
        // --- Halaman tampilan filter laporan ---
        $routes->get('dataasset', 'LaporanController::dataasset');
        $routes->get('kendaraan', 'LaporanController::kendaraan');
        $routes->get('pembelian', 'LaporanController::pembelian');
        $routes->get('pemakaian', 'LaporanController::pemakaian');

        // --- Ekspor Cetak/Export PDF & Excel ---
        
        // Laporan Data Aset
        $routes->get('asset/pdf', 'LaporanController::exportDataAssetPdf');
        $routes->get('asset/excel', 'LaporanController::exportDataAssetExcel');
        $routes->get('asset/sub-kategori', 'LaporanController::getSubKategoriByKategori');
        $routes->get('asset/nama-barang', 'LaporanController::getNamaBarangBySubKategori');
        $routes->get('asset/data-filter', 'LaporanController::getDataBarangByFilter');
        // Laporan Kendaraan
        $routes->get('kendaraan/pdf/(:segment)', 'LaporanController::cetakKendaraanPdf/$1');
        $routes->get('kendaraan/excel/(:segment)', 'LaporanController::exportKendaraanExcel/$1');
        // Laporan Pembelian
        $routes->get('pembelian/pdf', 'LaporanController::cetakPembelianPdf');
        $routes->get('pembelian/pdf/(:segment)', 'LaporanController::cetakPembelianPdf/$1');
        $routes->get('pembelian/excel', 'LaporanController::exportPembelianExcel');
        $routes->get('pembelian/excel/(:segment)', 'LaporanController::exportPembelianExcel/$1');
        // Laporan Pemakaian
        $routes->get('pemakaian', 'LaporanController::pemakaian');
        $routes->get('pemakaian/pdf', 'LaporanController::cetakPemakaianPdf');
        $routes->get('pemakaian/pdf/(:segment)', 'LaporanController::cetakPemakaianPdf/$1');
        $routes->get('pemakaian/excel', 'LaporanController::exportPemakaianExcel');
        $routes->get('pemakaian/excel/(:segment)', 'LaporanController::exportPemakaianExcel/$1');
    });
});
