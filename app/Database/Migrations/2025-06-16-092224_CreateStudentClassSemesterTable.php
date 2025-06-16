<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentClassSemesterTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'profile_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'class_semester_id' => [
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
        $this->forge->createTable('student_class_semester');
        $this->forge->addForeignKey('profile_id', 'profile', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('class_semester_id', 'class_semester', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by_id', 'user', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('updated_by_id', 'user', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('student_class_semester');
    }
}
