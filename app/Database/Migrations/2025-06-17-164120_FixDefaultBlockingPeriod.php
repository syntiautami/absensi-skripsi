<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixDefaultBlockingPeriod extends Migration
{
    public function up()
    {
        // Pastikan tipe field-nya sesuai ya (misal INT, SMALLINT, dsb)
        $this->db->query("ALTER TABLE class_semester MODIFY COLUMN blocking_period INT DEFAULT 15");
    }

    public function down()
    {
        $this->forge->modifyColumn('class_semester', [
            'blocking_period' => [
                'type' => 'INT',
                'default' => 15
            ],
        ]);
    }
}
