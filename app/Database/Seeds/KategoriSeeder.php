<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kode_kategori' => 'ELK',
                'nama_kategori' => 'Elektronik',
            ],
            [
                'kode_kategori' => 'KMP',
                'nama_kategori' => 'Komputer & IT',
            ],
            [
                'kode_kategori' => 'FRN',
                'nama_kategori' => 'Furniture',
            ],
            [
                'kode_kategori' => 'KND',
                'nama_kategori' => 'Kendaraan',
            ],
            [
                'kode_kategori' => 'BNG',
                'nama_kategori' => 'Bangunan',
            ],
            [
                'kode_kategori' => 'MES',
                'nama_kategori' => 'Mesin & Peralatan',
            ],
            [
                'kode_kategori' => 'OFC',
                'nama_kategori' => 'Perlengkapan Kantor',
            ],
            [
                'kode_kategori' => 'LBR',
                'nama_kategori' => 'Laboratorium',
            ],
        ];

        // Insert data jika belum ada (avoid duplicate)
        foreach ($data as $item) {
            $existing = $this->db->table('kategori')
                                ->where('kode_kategori', $item['kode_kategori'])
                                ->get()
                                ->getRow();
            
            if (!$existing) {
                $this->db->table('kategori')->insert($item);
            }
        }
    }
}
