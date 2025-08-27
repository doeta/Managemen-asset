<?php

namespace App\Models;

use CodeIgniter\Model;

class KendaraanModel extends Model
{
    protected $table            = 'kendaraan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_pengguna','id_lokasi', 'nama_kendaraan', 'no_polisi','nomor_polisi_sebelumnya', 'warna', 'model_kendaraan', 'merk_kendaraan', 'tipe_kendaraan', 'harga', 'tahun_kendaraan', 'no_rangka', 'no_stnk', 'no_mesin', 'no_bpkb', 'pembayaran_pajak', 'masa_berlaku', 'harga_pajak'];

    public function getKendaraanWithPengguna()
    {
        return $this->select('kendaraan.*, pengguna.nama_pengguna, pengguna.nip, pengguna.no_hp, pengguna.alamat')
                    ->join('pengguna', 'pengguna.id = kendaraan.id_pengguna', 'left');
                    
    }

    public function getKendaranWithLokasi()
    {
        return $this->select('kendaraan.*, lokasi.nama_lokasi')
                    ->join('lokasi', 'lokasi.id = kendaraan.id_lokasi', 'left');
    }
    
    public function getRiwayatKendaraan($id_kendaraan)
{
    return $this->db->table('riwayat_kendaraan')
        ->select('riwayat_kendaraan.*, pengguna.nama_pengguna')
        ->join('pengguna', 'pengguna.id = riwayat_kendaraan.id_pengguna', 'left')
        ->where('riwayat_kendaraan.id_kendaraan', $id_kendaraan);
}
    
    
}
