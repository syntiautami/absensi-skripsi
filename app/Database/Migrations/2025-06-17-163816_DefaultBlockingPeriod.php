<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DefaultBlockingPeriod extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('class_semester', [
            'blocking_period' => [
                'type' => 'INT',
                'default' => 15
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('class_semester', [
            'blocking_period' => [
                'type' => 'INT',
                'null' => true,
                'default' => 15
            ],
        ]);
    }
}
