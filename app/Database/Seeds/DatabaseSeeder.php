<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Jalankan semua seeder dalam urutan yang benar
        $this->call('UsersSeeder');
        $this->call('KategoriSeeder');
        $this->call('LokasiSeeder');
    }
}
