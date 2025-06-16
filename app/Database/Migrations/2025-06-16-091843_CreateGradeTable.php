<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGradeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'active' => [
                'type'  => 'BOOLEAN',
                'default' => true,
            ],
            'order' => [
                'type' => 'TINYINT',
                'constraint' => 1,
            ],
            'section_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('grade');
        $this->forge->addForeignKey('section_id', 'section', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by_id', 'user', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('updated_by_id', 'user', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('grade');
    }
}
