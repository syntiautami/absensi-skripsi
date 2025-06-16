<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClassSemesterSubjectTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'class_semester_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'subject_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'active' => [
                'type'  => 'BOOLEAN',
                'default' => true,
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
        $this->forge->createTable('class_semester_subject');
        $this->forge->addForeignKey('class_semester_id', 'class_semester', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('subject_id', 'subject', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by_id', 'user', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('updated_by_id', 'user', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('class_semester_subject');
    }
}
