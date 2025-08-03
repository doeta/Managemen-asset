<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatKendaraanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
                'unsigned' => true,
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
            'nama_kendaraan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'no_polisi' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
               
            ],
            'nomor_polisi_sebelumnya' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                
            ],
            'warna' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'model_kendaraan' => [
                'type' => 'ENUM',
                'constraint' => ['motor', 'mobil'],
            ],
            'merk_kendaraan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'tipe_kendaraan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'harga' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'tahun_kendaraan' => [
                'type' => 'YEAR',
            ],
            'no_rangka' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'no_stnk' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'no_mesin' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'no_bpkb' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'pembayaran_pajak' => [
                'type' => 'DATE',
            ],
            'harga_pajak' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'masa_berlaku' => [
                'type' => 'DATE',
            ],
        ]);

        $this->forge->addForeignKey('id_pengguna', 'pengguna', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_lokasi', 'lokasi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey('id', true);
        $this->forge->createTable('kendaraan');


    }

    public function down()
    {
        $this->forge->dropTable('kendaraan');
    }
}
