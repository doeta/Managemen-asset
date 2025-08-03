<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRiwayatAssetTable extends Migration
{
     public function up()
    {
        $this->forge->addField([
            'id_riwayat' => [
                'type'           => 'INT',
                'auto_increment' => true,
                'unsigned'       => true,
            ],
            'id_asset' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'kode_sub_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
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
            'jumlah_dibeli' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false,
            ],
            'harga_satuan' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => false,
            ],
            'total_harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => false,
            ],
            'tanggal_pembelian' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_riwayat', true);
        $this->forge->addForeignKey('id_asset', 'asset', 'id', 'CASCADE', 'RESTRICT');;
        $this->forge->createTable('riwayat_pembelian');
    }

    public function down()
    {
        $this->forge->dropTable('riwayat_pembelian');
    }
}