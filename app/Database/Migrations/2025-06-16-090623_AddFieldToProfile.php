<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldToProfile extends Migration
{
    public function up()
    {
        $fields = [
            'barcode_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'profile_photo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_by_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
        ];
        $this->forge->addColumn('profile', $fields);
        $this->forge->addForeignKey('created_by_id', 'user', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('profile', 'profile_created_by_id_foreign');
        $this->forge->dropColumn('profile', ['barcode_number', 'profile_photo', 'created_by_id']);
    }
}
