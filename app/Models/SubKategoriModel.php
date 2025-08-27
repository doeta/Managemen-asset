<?php

namespace App\Models;

use CodeIgniter\Model;

class SubKategoriModel extends Model
{
    protected $table            = 'sub_kategori';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kode_kategori', 'kode_sub_kategori', 'nama_sub_kategori', 'deskripsi_sub_kategori'];

  public function getSubKategoriWithKategori($kategori = null)
{
    $builder = $this->select('sub_kategori.*, kategori.nama_kategori, kategori.kode_kategori, COUNT(CASE WHEN barang.status != "habis terpakai" THEN barang.id END) as jumlah_barang')
                    ->join('kategori', 'kategori.kode_kategori = sub_kategori.kode_kategori')
                    ->join('asset', 'asset.kode_sub_kategori = sub_kategori.kode_sub_kategori', 'left')
                    ->join('barang', 'barang.id_asset = asset.id', 'left')
                    ->groupBy('sub_kategori.kode_sub_kategori');

    if ($kategori !== null) {
        $builder->where('kategori.nama_kategori', $kategori);
    }

    return $builder->findAll();
}


}

