<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGracePeriodClassSemester extends Migration
{
    public function up()
    {
        $fields = [
            'grace_period' => [
                'type'       => 'int',
                'null'       => true,
            ],
        ];
        $this->forge->addColumn('class_semester', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('class_semester', ['grace_period']);
    }
}
