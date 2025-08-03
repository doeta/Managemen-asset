<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenggunaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
                'unsigned'       => true,
                
                
            ],
            'nama_pengguna'    => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'nip'    => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'no_hp'    => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'alamat'    => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'id_lokasi'    => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null' => true,
            ],
            
        ]);

        $this->forge->addForeignKey('id_lokasi', 'lokasi', 'id', 'CASCADE', 'CASCADE');

        $this->forge->addKey('id', true);
        $this->forge->createTable('pengguna');
    }
    


    public function down()
    {
        $this->forge->dropTable('pengguna');
    }
}
