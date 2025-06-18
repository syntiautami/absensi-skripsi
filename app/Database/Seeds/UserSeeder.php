<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'first_name' => 'Budi',
                'last_name'  => 'Santoso',
                'email'      => 'budi@mailinator.com',
                'username'   => 'budi',
                'password'   => password_hash('123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Rina',
                'last_name'  => 'Kusuma',
                'email'      => 'rina@mailinator.com',
                'username'   => 'rina',
                'password'   => password_hash('123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Agus',
                'last_name'  => 'Prasetyo',
                'email'      => 'agus@mailinator.com',
                'username'   => 'agus',
                'password'   => password_hash('123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Dewi',
                'last_name'  => 'Sari',
                'email'      => 'dewi@mailinator.com',
                'username'   => 'dewi',
                'password'   => password_hash('123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Andi',
                'last_name'  => 'Wijaya',
                'email'      => 'andi@mailinator.com',
                'username'   => 'andi',
                'password'   => password_hash('123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];


        $this->db->table('user')->insertBatch($data);
    }
}
