<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubKategoriTable extends Migration
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
                'auto_increment' => true,
            ],
            'kode_sub_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'nama_sub_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
           'deskripsi_sub_kategori' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            
        ]);

        // Primary key
        $this->forge->addKey('id', true);

        // Tambahkan unique key pada kode_sub_kategori
        $this->forge->addKey('kode_sub_kategori', false, true);

        // Foreign key
        $this->forge->addForeignKey('kode_kategori', 'kategori', 'kode_kategori', 'CASCADE', 'RESTRICT');

        // Buat tabel
        $this->forge->createTable('sub_kategori');
    }

    public function down()
    {
        $this->forge->dropTable('sub_kategori', true);
    }
}