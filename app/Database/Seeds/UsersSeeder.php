<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username' => 'admin',
            'nama' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),

        ];

        //using query builder
        $this->db->table('users')->insert($data);
    }
}
