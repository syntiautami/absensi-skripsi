<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleAttendanceSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name' => 'attendance',
        ];

        $this->db->table('role')->insert($data);
    }
}
