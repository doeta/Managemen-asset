<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatPembelianModel extends Model
{
    protected $table            = 'riwayat_pembelian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [ 'id_riwayat', 'id_asset', 'kode_sub_kategori', 'kode_kategori', 'nama_barang', 'kode_barang', 'deskripsi_barang', 'jumlah_dibeli', 'harga_satuan', 'total_harga', 'tanggal_pembelian' ];

public function getRiwayatWithAsset($id_asset = null)
{
    $builder = $this->db->table($this->table);
    $builder->select('riwayat_pembelian.*, 
                      sub_kategori.nama_sub_kategori, 
                      kategori.nama_kategori');
    $builder->join('sub_kategori', 'sub_kategori.kode_sub_kategori = riwayat_pembelian.kode_sub_kategori', 'left');
    $builder->join('kategori', 'kategori.kode_kategori = riwayat_pembelian.kode_kategori', 'left');

    if ($id_asset !== null) {
        $builder->where('riwayat_pembelian.id_asset', $id_asset);
    }

    $builder->orderBy('riwayat_pembelian.tanggal_pembelian', 'DESC');

    return $builder->get()->getResultArray();
}



}
