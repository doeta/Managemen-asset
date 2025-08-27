<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_asset',
        'nama_barang',
        'kode_unik',
        'harga_barang',
        'tanggal_masuk',
        'status',
        'id_pengguna',
        'id_lokasi',
    ];

    // Fungsi untuk mendapatkan data barang beserta informasi asset, pengguna, dan lokasi
    public function getBarangWithAssetLocationPenggunaBySubKategori($kode_sub_kategori)
    {
    return $this->select('barang.*, asset.kode_barang, asset.nama_barang, asset.kode_kategori, asset.kode_sub_kategori, pengguna.nama_pengguna, lokasi.nama_lokasi')
            ->join('asset', 'asset.id = barang.id_asset')
            ->join('pengguna', 'pengguna.id = barang.id_pengguna', 'left')
            ->join('lokasi', 'lokasi.id = barang.id_lokasi', 'left')
            ->where('asset.kode_sub_kategori', $kode_sub_kategori)
            ->where('barang.status !=', 'habis terpakai')
            ->orderBy('asset.nama_barang', 'ASC')
            ->findAll();
    }

    // Fungsi untuk mencari barang
    public function search($keyword)
    {
        return $this->select('barang.*, asset.kode_barang, asset.nama_barang, asset.kode_kategori, asset.kode_sub_kategori, pengguna.nama_pengguna, lokasi.nama_lokasi')
                    ->join('asset', 'asset.id = barang.id_asset')
                    ->join('pengguna', 'pengguna.id = barang.id_pengguna', 'left')
                    ->join('lokasi', 'lokasi.id = barang.id_lokasi', 'left')
                    ->where('barang.status !=', 'habis terpakai')
                    ->groupStart()
                        ->like('barang.nama_barang', $keyword)
                        ->orLike('barang.kode_unik', $keyword)
                        ->orLike('asset.nama_barang', $keyword)
                    ->groupEnd()
                    ->orderBy('barang.nama_barang', 'ASC')
                    ->findAll();
    }

    public function getBarangById($id)
    {
        return $this->select('barang.*, asset.kode_sub_kategori')
                    ->join('asset', 'asset.id = barang.id_asset')
                    ->where('barang.id', $id)
                    ->first();
    }
    
    public function getBarangWithSubkategori($kode_sub_kategori)
    {
    return $this->select('barang.*, asset.kode_barang, asset.nama_barang, asset.kode_kategori, asset.kode_sub_kategori')
            ->join('asset', 'asset.id = barang.id_asset')
            ->where('asset.kode_sub_kategori', $kode_sub_kategori)
            ->where('barang.status !=', 'habis terpakai')
            ->findAll();
    }

    // Method untuk standarisasi status barang
    public function getStandardStatus()
    {
        return [
            'tersedia' => 'Tersedia',
            'terpakai' => 'Terpakai', 
            'rusak' => 'Rusak',
            'maintenance' => 'Maintenance',
            'habis_terpakai' => 'Habis Terpakai'
        ];
    }

    // Method untuk update jumlah barang di tabel asset
    public function updateAssetQuantity($id_asset, $change)
    {
        $db = \Config\Database::connect();
        $db->table('asset')
           ->set('jumlah_barang', "jumlah_barang + {$change}", false)
           ->where('id', $id_asset)
           ->update();
    }

    // Method untuk sinkronisasi jumlah barang berdasarkan status
    public function syncAssetQuantity($id_asset)
    {
        $db = \Config\Database::connect();
        
        // Hitung jumlah barang dengan status tersedia
        $tersedia = $db->table('barang')
                      ->where('id_asset', $id_asset)
                      ->where('status', 'tersedia')
                      ->countAllResults();
        
        // Update jumlah di tabel asset
        $db->table('asset')
           ->set('jumlah_barang', $tersedia)
           ->where('id', $id_asset)
           ->update();
    }

    // Method untuk mendapatkan riwayat pemakaian barang
    public function getPemakaianHistory($id_asset)
    {
        $db = \Config\Database::connect();
        return $db->table('pemakaian')
                  ->select('pemakaian.*, pengguna.nama_pengguna, lokasi.nama_lokasi')
                  ->join('pengguna', 'pengguna.id = pemakaian.id_pengguna', 'left')
                  ->join('lokasi', 'lokasi.id = pemakaian.id_lokasi', 'left')
                  ->where('pemakaian.id_asset', $id_asset)
                  ->orderBy('pemakaian.tanggal_mulai', 'DESC')
                  ->get()
                  ->getResultArray();
    }

    // Method untuk mendapatkan statistik status per kategori
    public function getStatistikStatusPerKategori()
    {
    return $this->select('kategori, 
                         SUM(CASE WHEN status = "tersedia" THEN 1 ELSE 0 END) AS tersedia,
                         SUM(CASE WHEN status = "terpakai" THEN 1 ELSE 0 END) AS terpakai')
                ->groupBy('kategori')
                ->findAll();
    }



}
