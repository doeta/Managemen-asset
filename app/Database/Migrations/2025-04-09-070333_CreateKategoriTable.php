<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKategoriTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'nama_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);

        // Tambahkan primary key
        $this->forge->addKey('id', true);

        // Tambahkan unique key pada kode_kategori
        $this->forge->addKey('kode_kategori', false, true);

        // Buat tabel
        $this->forge->createTable('kategori');
    }

    public function down()
    {
        $this->forge->dropTable('kategori');
    }
}

