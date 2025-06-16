<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'first_name' => 'Syntia',
                'last_name'  => 'Tri Utami',
                'email'      => 'syntia@mailinator.com',
                'username'   => 'syntia',
                'password'   => password_hash('123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Admin',
                'last_name'  => 'User',
                'email'      => 'admin@example.com',
                'username'   => 'admin',
                'password'   => password_hash('123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('user')->insertBatch($data);
    }
}
