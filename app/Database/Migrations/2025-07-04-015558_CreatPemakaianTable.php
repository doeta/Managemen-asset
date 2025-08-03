<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatPemakaianTable extends Migration
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
            'kode_sub_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'id_asset' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'id_lokasi' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'id_pengguna' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'jumlah_digunakan' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'satuan_penggunaan' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'tanggal_mulai' => [
                'type' => 'DATE',
            ],
            'tanggal_selesai' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['tersedia', 'habis terpakai', 'terpakai'],
                'default'    => 'tersedia',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kode_kategori', 'kategori', 'kode_kategori', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('kode_sub_kategori', 'sub_kategori', 'kode_sub_kategori', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_asset', 'asset', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_lokasi', 'lokasi', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_pengguna', 'pengguna', 'id', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('pemakaian');
    }

    public function down()
    {
        $this->forge->dropTable('pemakaian', true);
    }
}
