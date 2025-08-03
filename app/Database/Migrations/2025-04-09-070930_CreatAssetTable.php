<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssetTable extends Migration
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
            'kode_sub_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'auto_increment' => true,
            ],
            'kode_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'kode_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],

            'deskripsi_barang' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'jumlah_barang' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'harga_barang' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'total_harga_barang' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'tanggal_masuk' => [
                'type'       => 'DATE',
            ],
           
        ]);

        // Primary key
        $this->forge->addKey('id', true);

        // Foreign key
        $this->forge->addForeignKey('kode_sub_kategori', 'sub_kategori', 'kode_sub_kategori', 'CASCADE', 'RESTRICT');
    
        // Foreign key
        $this->forge->addForeignKey('kode_kategori', 'kategori', 'kode_kategori', 'CASCADE', 'RESTRICT');
        // Create table
        $this->forge->createTable('asset');
    }

    public function down()
    {
        // Drop table
        $this->forge->dropTable('asset', true);
    }
}

