<?php

namespace App\Models;
use CodeIgniter\Model;
class LokasiModel extends Model
{
    protected $table            = 'lokasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kode_lokasi', 'nama_lokasi', 'deskripsi_lokasi'];

    
}
