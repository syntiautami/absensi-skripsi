<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'profile_id' => 1,
                'role_id'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'profile_id' => 1,
                'role_id'    => 2,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('user_role')->insertBatch($data);
    }
}
