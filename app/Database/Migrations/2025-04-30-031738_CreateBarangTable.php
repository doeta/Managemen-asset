<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBarangTable extends Migration
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
            'id_asset' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,

            ],
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'kode_unik' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'harga_barang' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'tanggal_masuk' => [
                'type' => 'DATE',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['tersedia', 'habis terpakai', 'terpakai'],
                'default'    => 'tersedia',
            ],
            'id_pengguna' => [ // foreign key ke pengguna
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null' => true,
                
            ],
            'id_lokasi' => [ // foreign key ke lokasi
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        // Foreign Keys
        $this->forge->addForeignKey('id_asset', 'asset', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_pengguna', 'pengguna', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_lokasi', 'lokasi', 'id', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('barang');
    }

    public function down()
    {
        $this->forge->dropTable('barang');
    }
}
