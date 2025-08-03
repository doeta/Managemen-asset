<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DasboardController extends BaseController
{
    public function index()
    {
        $barangModel = new \App\Models\BarangModel();
        $kendaraanModel = new \App\Models\KendaraanModel();

        // Koneksi DB manual untuk agregasi jumlah barang per kategori
        $db = \Config\Database::connect();
        $builder = $db->table('barang');
        $builder->select('kategori.nama_kategori, COUNT(barang.id) as jumlah');
        $builder->join('asset', 'asset.id = barang.id_asset');
        $builder->join('kategori', 'kategori.kode_kategori = asset.kode_kategori');
        $builder->groupBy('kategori.nama_kategori');
        $query = $builder->get();
        $barangPerKategori = $query->getResultArray();

        // Kendaraan
        $kendaraanMobil = $kendaraanModel->where('model_kendaraan', 'mobil')->countAllResults();
        $kendaraanMotor = $kendaraanModel->where('model_kendaraan', 'motor')->countAllResults();

        return view('dashboard', [
            'barangPerKategori' => $barangPerKategori,
            'kendaraanMobil' => $kendaraanMobil,
            'kendaraanMotor' => $kendaraanMotor,
            'menu' => 'dashboard',
        ]);
    }
}
