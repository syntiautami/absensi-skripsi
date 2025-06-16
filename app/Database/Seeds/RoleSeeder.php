<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'teacher'],
            ['name' => 'admin']
        ];

        // Insert multiple roles
        $this->db->table('role')->insertBatch($data);
    }
}
