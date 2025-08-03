<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatModel extends Model
{
    protected $table            = 'riwayat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_asset', 'id_pengguna','id_barang', 'tanggal_mulai', 'tanggal_selesai', 'keterangan', 'jumlah_digunakan', 'satuan_penggunaan'];

    public function getRiwayatWithAsset($id_asset)
    {
        return $this->select('riwayat.*, asset.nama_barang, asset.kode_unik')
                    ->join('asset', 'asset.id = riwayat.id_asset')
                    ->where('riwayat.id_asset', $id_asset)
                    ->findAll();
    }
    public function getRiwayatWithPengguna($id_pengguna)
    {
        return $this->select('riwayat.*, pengguna.nama_lengkap')
                    ->join('pengguna', 'pengguna.id = riwayat.id_pengguna')
                    ->where('riwayat.id_pengguna', $id_pengguna)
                    ->findAll();
    }

    public function getRiwayatWithBarang($id_barang)
    {
        return $this->select('riwayat.*, barang.nama_barang')
                    ->join('barang', 'barang.id = riwayat.id_barang')
                    ->where('riwayat.id_barang', $id_barang)
                    ->findAll();
    }
    
}
