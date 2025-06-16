<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClassSemesterTable extends Migration
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
            'clock_in' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'clock_out' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'blocking_period' => [
                'type' => 'INT',
                'null' => true,
            ],
            'active' => [
                'type'  => 'BOOLEAN',
                'default' => true,
            ],
            'grade_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'semester_id' => [
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
        $this->forge->createTable('class_semester');
        $this->forge->addForeignKey('semester_id', 'semester', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('grade_id', 'grade', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by_id', 'user', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('updated_by_id', 'user', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('class_semester');
    }
}
