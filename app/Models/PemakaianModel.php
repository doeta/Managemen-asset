<?php

namespace App\Models;

use CodeIgniter\Model;

class PemakaianModel extends Model
{
    protected $table            = 'pemakaian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'kode_kategori',
        'kode_sub_kategori',
        'id_asset', // ✅ gunakan field ini
        'id_lokasi',
        'id_pengguna',
        'jumlah_digunakan',
        'satuan_penggunaan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status', // ✅ tambahkan status
        'keterangan'
    ];

    // ✅ Ambil semua pemakaian dengan data relasi asset, lokasi, pengguna
public function getPemakaianWithAsset($tahun = null, $kategori = null)
{
    $builder = $this->select('pemakaian.*, asset.nama_barang, asset.kode_barang, asset.kode_kategori, kategori.nama_kategori, asset.kode_sub_kategori, sub_kategori.nama_sub_kategori, lokasi.nama_lokasi, pengguna.nama_pengguna')
        ->join('asset', 'asset.id = pemakaian.id_asset')
        ->join('kategori', 'kategori.kode_kategori = asset.kode_kategori', 'left')
        ->join('sub_kategori', 'sub_kategori.kode_sub_kategori = asset.kode_sub_kategori', 'left')
        ->join('lokasi', 'lokasi.id = pemakaian.id_lokasi', 'left')
        ->join('pengguna', 'pengguna.id = pemakaian.id_pengguna', 'left');

    if (!empty($tahun) && $tahun !== 'Semua') {
        $builder->where('YEAR(pemakaian.tanggal_mulai)', $tahun);
    }

    if (!empty($kategori) && $kategori !== 'Semua') {
        $builder->where('kategori.nama_kategori', $kategori);
    }

    return $builder->findAll();
}




    // ✅ Ambil satu data berdasarkan ID pemakaian
    public function getPemakaianWithAssetById($id)
    {
        return $this->select('pemakaian.*, asset.nama_barang, lokasi.nama_lokasi, pengguna.nama_pengguna')
                    ->join('asset', 'asset.id = pemakaian.id_asset', 'left')
                    ->join('lokasi', 'lokasi.id = pemakaian.id_lokasi', 'left')
                    ->join('pengguna', 'pengguna.id = pemakaian.id_pengguna', 'left')
                    ->where('pemakaian.id', $id)
                    ->first();
    }
}
