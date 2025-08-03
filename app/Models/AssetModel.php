<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model
{
    protected $table            = 'asset'; // Nama tabel asset
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'nama_barang',
        'kode_barang',
        'kode_kategori',
        'kode_sub_kategori',
        'deskripsi_barang',
        'jumlah_barang',
        'harga_barang',
        'total_harga_barang',
        'tanggal_masuk',
        'status',
    ];

    // Fungsi untuk mengambil data asset dengan join ke tabel kategori
    public function getAssetsWithKategori()
    {
        return $this->select('asset.*, kategori.kode_kategori, kategori.nama_kategori')
                    ->join('kategori', 'kategori.kode_kategori = asset.kode_kategori', 'left')
                    ->findAll();
                    
    }

    public function getAssetWithKategoriWhere($id)
    {
        return $this->select('asset.*, kategori.kode_kategori, kategori.nama_kategori, sub_kategori.nama_sub_kategori')
                    ->join('kategori', 'kategori.kode_kategori = asset.kode_kategori', 'left')
                    ->join('sub_kategori', 'sub_kategori.kode_sub_kategori = asset.kode_sub_kategori', 'left')
                    ->find($id);
    }

    public function getAssetWithKategoriPaginated($perPage = 10)
    {
        return $this->select('asset.*, kategori.kode_kategori, kategori.nama_kategori, sub_kategori.nama_sub_kategori')
                    ->join('kategori', 'kategori.kode_kategori = asset.kode_kategori', 'left')
                    ->join('sub_kategori', 'sub_kategori.kode_sub_kategori = asset.kode_sub_kategori', 'left')
                    ->findAll();
    }

    // funngsi untuk mencari asset berdasarkan nama atau kode

    public function search($keyword)
    {
        return $this->select('asset.*, kategori.kode_kategori, kategori.nama_kategori, sub_kategori.nama_sub_kategori')
                    ->join('kategori', 'kategori.kode_kategori = asset.kode_kategori', 'left')
                    ->join('sub_kategori', 'sub_kategori.kode_sub_kategori = asset.kode_sub_kategori', 'left')
                    ->groupStart()
                        ->like('asset.nama_barang', $keyword)
                        ->orLike('asset.kode_barang', $keyword)
                        ->orLike('kategori.nama_kategori', $keyword)
                        ->orLike('sub_kategori.nama_sub_kategori', $keyword)
                    ->groupEnd()
                    ->findAll();
    }

    public function getAssetByKategori($kode_kategori)
    {
        return $this->where('kode_kategori', $kode_kategori)->findAll();
    }
    
    public function getAssetForPemakaian()
{
    return $this->select('asset.id, asset.nama_barang, asset.kode_kategori, asset.kode_sub_kategori, asset.jumlah_barang, asset.status')
        ->join('kategori', 'kategori.kode_kategori = asset.kode_kategori', 'left')
        ->join('sub_kategori', 'sub_kategori.kode_sub_kategori = asset.kode_sub_kategori', 'left')
        ->where('asset.jumlah_barang >', 0)
        ->orderBy('asset.nama_barang', 'ASC')
        ->findAll();
}


}
