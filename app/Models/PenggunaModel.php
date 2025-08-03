<?php

namespace App\Models;

use CodeIgniter\Model;

class PenggunaModel extends Model
{
    protected $table            = 'pengguna';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_pengguna', 'nip', 'no_hp', 'alamat', 'id_lokasi', ];

    public function getPenggunaWithLokasi()
    {
        return $this->select('pengguna.*, lokasi.nama_lokasi, lokasi.kode_lokasi')
                    ->join('lokasi', 'lokasi.id = pengguna.id_lokasi', 'left')
                    ->orderBy('pengguna.nama_pengguna', 'ASC')
                    ->findAll();
    }
}
