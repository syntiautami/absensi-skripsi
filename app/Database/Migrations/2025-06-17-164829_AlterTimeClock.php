<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTimeClock extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('class_semester', [
            'clock_in' => [
                'type'    => 'TIME',
                'null'    => true,
                'default' => null,
            ],
            'clock_out' => [
                'type'    => 'TIME',
                'null'    => true,
                'default' => null,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('class_semester', [
            'clock_in' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'clock_out' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);
    }

}
