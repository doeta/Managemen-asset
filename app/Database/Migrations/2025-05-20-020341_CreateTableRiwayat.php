<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableRiwayat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_asset' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'id_barang' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'id_pengguna' => [ // foreign key ke pengguna
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null' => true,
            ],
            'id_lokasi' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'tanggal_mulai' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'tanggal_selesai' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'keterangan' => [
                'type' => 'TEXT',
            ],
            'jumlah_digunakan' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'satuan_penggunaan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],


        ]);

              // Menambahkan foreign key ke tabel asset
        $this->forge->addForeignKey('id_asset', 'asset', 'id', 'CASCADE', 'CASCADE');
        // Menambahkan foreign key ke tabel pengguna
        $this->forge->addForeignKey('id_pengguna', 'pengguna', 'id', 'CASCADE', 'CASCADE');
        // Menambahkan foreign key ke tabel barang
        $this->forge->addForeignKey('id_barang', 'barang', 'id', 'CASCADE', 'CASCADE');
        // Menambahkan foreign key ke tabel lokasi
        $this->forge->addForeignKey('id_lokasi', 'lokasi', 'id', 'CASCADE', 'CASCADE');
        

        $this->forge->addKey('id', true);
        $this->forge->createTable('riwayat');

  
    }

    public function down()
    {
        $this->forge->dropTable('riwayat');
    }
}
