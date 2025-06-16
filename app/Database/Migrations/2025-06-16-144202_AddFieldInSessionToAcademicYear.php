<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldInSessionToAcademicYear extends Migration
{
    public function up()
    {
        $this->forge->addColumn('academic_year', [
            'in_session' => [
                'type'       => 'BOOLEAN',
                'null'       => false,
                'default'    => false,
                'after'      => 'name', // sesuaikan dengan nama kolom terakhir jika berbeda
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('academic_year', 'in_session');
    }
}
