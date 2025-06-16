<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldInSessionToSemester extends Migration
{
    public function up()
    {
        $this->forge->addColumn('semester', [
            'in_session' => [
                'type'       => 'BOOLEAN',
                'null'       => false,
                'default'    => false,
                'after'      => 'name' // sesuaikan dengan kolom terakhir di tabel semester jika perlu
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('semester', 'in_session');
    }
}
