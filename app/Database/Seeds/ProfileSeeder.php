<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'user_id'        => 1,
            'address'        => 'Jl. Contoh No. 123',
            'gender'         => 'female',
            'religion'       => 'Islam',
            'parent_email'   => 'ortu@example.com',
            'father_name'    => 'Budi',
            'mother_name'    => 'Ani',
            'nis'            => '12345678',
            'nisn'           => '87654321',
            'barcode_number' => 'BC123456789',
            'profile_photo'  => '',
            'created_by_id'  => 1,
            'created_at'     => date('Y-m-d H:i:s'),
        ];

        // Insert multiple roles
        $this->db->table('profile')->insertBatch($data);
    }
}
