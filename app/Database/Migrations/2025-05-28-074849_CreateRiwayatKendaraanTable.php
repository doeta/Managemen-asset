<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRiwayatKendaraanTable extends Migration
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
             'id_kendaraan' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'id_pengguna' => [ 
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
            'nomor_polisi' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
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
        ]);

        $this->forge->addKey('id', true); // Menambahkan primary key
        // Menambahkan foreign key ke tabel kendaraan
        $this->forge->addForeignKey('id_kendaraan', 'kendaraan', 'id', 'CASCADE', 'CASCADE');
        // Menambahkan foreign key ke tabel pengguna
        $this->forge->addForeignKey('id_pengguna', 'pengguna', 'id', 'CASCADE', 'CASCADE');
        // Menambahkan foreign key ke tabel lokasi
        $this->forge->addForeignKey('id_lokasi', 'lokasi', 'id', 'CASCADE ', 'CASCADE');
         
        $this->forge->createTable('riwayat_kendaraan');
    }



    public function down()
    {
        $this->forge->dropTable('riwayat_kendaraan', true); }
}
