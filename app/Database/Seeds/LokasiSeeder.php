<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LokasiSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kode_lokasi' => 'GD01',
                'nama_lokasi' => 'Gedung Utama',
                'deskripsi_lokasi' => 'Gedung pusat administrasi dan manajemen',
            ],
            [
                'kode_lokasi' => 'GD02',
                'nama_lokasi' => 'Gedung Operasional',
                'deskripsi_lokasi' => 'Gedung untuk kegiatan operasional harian',
            ],
            [
                'kode_lokasi' => 'GDG01',
                'nama_lokasi' => 'Gudang Penyimpanan',
                'deskripsi_lokasi' => 'Gudang untuk penyimpanan barang dan asset',
            ],
            [
                'kode_lokasi' => 'LAB01',
                'nama_lokasi' => 'Laboratorium',
                'deskripsi_lokasi' => 'Ruang laboratorium dan penelitian',
            ],
            [
                'kode_lokasi' => 'WS01',
                'nama_lokasi' => 'Workshop',
                'deskripsi_lokasi' => 'Area workshop dan maintenance',
            ],
            [
                'kode_lokasi' => 'PKR01',
                'nama_lokasi' => 'Area Parkir',
                'deskripsi_lokasi' => 'Area parkir kendaraan operasional',
            ],
            [
                'kode_lokasi' => 'IT01',
                'nama_lokasi' => 'Ruang Server',
                'deskripsi_lokasi' => 'Ruang server dan IT infrastructure',
            ],
            [
                'kode_lokasi' => 'MTG01',
                'nama_lokasi' => 'Ruang Rapat',
                'deskripsi_lokasi' => 'Ruang meeting dan conference',
            ],
        ];

        // Insert data jika belum ada (avoid duplicate)
        foreach ($data as $item) {
            $existing = $this->db->table('lokasi')
                                ->where('kode_lokasi', $item['kode_lokasi'])
                                ->get()
                                ->getRow();
            
            if (!$existing) {
                $this->db->table('lokasi')->insert($item);
            }
        }
    }
}
