<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatKendaraan extends Model
{
    protected $table            = 'riwayat_kendaraan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_kendaraan', 'id_pengguna', 'id_lokasi', 'nomor_polisi', 'tanggal_mulai', 'tanggal_selesai', 'keterangan'];

   public function getRiwayatKendaranWithPengguna()
{
    return $this->select('riwayat_kendaraan.*, pengguna.nama_pengguna, lokasi.nama_lokasi')
        ->join('pengguna', 'riwayat_kendaraan.id_pengguna = pengguna.id', 'left')
        ->join('lokasi', 'riwayat_kendaraan.id_lokasi = lokasi.id', 'left')
        ->findAll();
}

    public function getRiwayatKendaraanWithKendaraan()
{
    return $this->select('riwayat_kendaraan.*, kendaraan.nama_kendaraan, kendaraan.no_polisi')
        ->join('kendaraan', 'riwayat_kendaraan.id_kendaraan = kendaraan.id', 'left')
        ->findAll();
}

public function getRiwayatKendaraanWithLokasi()
{
    return $this->select('riwayat_kendaraan.*, lokasi.nama_lokasi')
        ->join('lokasi', 'riwayat_kendaraan.id_lokasi = lokasi.id', 'left')
        ->findAll();
}


    public function getRiwayatByKendaraan($id_kendaraan)
    {
        return $this->select('riwayat_kendaraan.*, pengguna.nama_pengguna, lokasi.nama_lokasi')
            ->join('pengguna', 'pengguna.id = riwayat_kendaraan.id_pengguna', 'left')
            ->join('lokasi', 'lokasi.id = riwayat_kendaraan.id_lokasi', 'left')
            ->where('riwayat_kendaraan.id_kendaraan', $id_kendaraan)
            ->orderBy('tanggal_mulai', 'DESC')
            ->findAll();
    }
}
