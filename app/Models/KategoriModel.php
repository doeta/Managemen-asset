<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table            = 'kategori';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kode_kategori', 'nama_kategori','deskripsi_kategori'];

    public function getWithKategori()
    {
        return $this->select('sub_kategori.*, kategori.nama_kategori, kategori.kode_kategori')
                    ->join('kategori', 'kategori.id = sub_kategori.kategori_id', 'left');
    }
    

    
    

}
