<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProfileTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id'     => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'gender' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'religion' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'parent_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'father_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'mother_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nis' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nisn' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by_id' => [
                'type' => 'int',
                'unsigned' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('profile');
        $this->forge->addForeignKey('user_id', 'user', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('updated_by_id', 'user', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('profile');
    }
}
