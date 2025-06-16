<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserRoleTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'profile_id'     => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'role_id'     => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('user_role');
        $this->forge->addForeignKey('profile_id', 'profile', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('role_id', 'role', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('user_role');
    }
}
