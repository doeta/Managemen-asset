<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLokasiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
                
            ],
            'kode_lokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'nama_lokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'deskripsi_lokasi' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('lokasi');
    }

    public function down()
    {
        $this->forge->dropTable('lokasi');
    }
}
