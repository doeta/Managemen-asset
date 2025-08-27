<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DasboardController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Models
        $barangModel = new \App\Models\BarangModel();
        $kendaraanModel = new \App\Models\KendaraanModel();
        $penggunaModel = new \App\Models\PenggunaModel();
        $pemakaianModel = new \App\Models\PemakaianModel();

        // ==== TOTAL ASET (Barang + Kendaraan) ====
    $totalBarang = $barangModel->where('status !=', 'habis terpakai')->countAllResults();
        $totalKendaraan = $kendaraanModel->countAll();
        $totalAset = $totalBarang + $totalKendaraan;

        // ==== TOTAL NILAI ASET (Barang + Kendaraan) ====
        $nilaiBarang = $barangModel->selectSum('harga_barang')->first()['harga_barang'] ?? 0;
        $nilaiKendaraan = $kendaraanModel->selectSum('harga')->first()['harga'] ?? 0;
        $totalNilaiAset = $nilaiBarang + $nilaiKendaraan;

        // ==== ASET PER KATEGORI (Hanya Barang) ====
    $builder = $db->table('barang');
    $builder->select('kategori.nama_kategori, COUNT(barang.id) as jumlah');
    $builder->join('asset', 'asset.id = barang.id_asset');
    $builder->join('kategori', 'kategori.kode_kategori = asset.kode_kategori');
    $builder->where('barang.status !=', 'habis terpakai');
    $builder->groupBy('kategori.nama_kategori');
    $barangPerKategori = $builder->get()->getResultArray();

        // ==== KENDARAAN (Untuk statistik kendaraan dinas saja) ====
        $kendaraanMobil = $kendaraanModel->where('model_kendaraan', 'mobil')->countAllResults();
        $kendaraanMotor = $kendaraanModel->where('model_kendaraan', 'motor')->countAllResults();

        // ==== PENGGUNA AKTIF (Yang punya Barang atau Kendaraan) ====
        // Users dengan barang
        $userBarangBuilder = $db->table('pengguna');
        $userBarangBuilder->select('pengguna.id');
        $userBarangBuilder->join('barang', 'barang.id_pengguna = pengguna.id');
        $userBarangBuilder->distinct();
        $userWithBarang = $userBarangBuilder->get()->getResultArray();
        $userBarangIds = array_column($userWithBarang, 'id');

        // Users dengan kendaraan
        $userKendaraanBuilder = $db->table('pengguna');
        $userKendaraanBuilder->select('pengguna.id');
        $userKendaraanBuilder->join('kendaraan', 'kendaraan.id_pengguna = pengguna.id');
        $userKendaraanBuilder->distinct();
        $userWithKendaraan = $userKendaraanBuilder->get()->getResultArray();
        $userKendaraanIds = array_column($userWithKendaraan, 'id');

        // Gabungkan dan hitung unique users
        $allActiveUserIds = array_unique(array_merge($userBarangIds, $userKendaraanIds));
        $penggunaAktif = count($allActiveUserIds);

        // ==== TOTAL PEMAKAIAN (Barang + Kendaraan) ====
        $totalPemakaianBarang = $pemakaianModel->countAll();
        
        // Cek apakah ada tabel pemakaian kendaraan
        $totalPemakaianKendaraan = 0;
        if ($db->tableExists('pemakaian_kendaraan')) {
            $pemakaianKendaraanBuilder = $db->table('pemakaian_kendaraan');
            $totalPemakaianKendaraan = $pemakaianKendaraanBuilder->countAllResults();
        }
        
        $totalPemakaian = $totalPemakaianBarang + $totalPemakaianKendaraan;

        // ==== PAJAK JATUH TEMPO ====
        $pajakJatuhTempo = $kendaraanModel
            ->where('masa_berlaku <=', date('Y-m-d', strtotime('+30 days')))
            ->countAllResults();

        return view('dashboard', [
            'totalAset' => $totalAset,
            'totalNilaiAset' => $totalNilaiAset,
            'barangPerKategori' => $barangPerKategori,
            'kendaraanMobil' => $kendaraanMobil,
            'kendaraanMotor' => $kendaraanMotor,
            'penggunaAktif' => $penggunaAktif,
            'totalPemakaian' => $totalPemakaian,
            'pajakJatuhTempo' => $pajakJatuhTempo,
            'menu' => 'dashboard',
        ]);
    }
}