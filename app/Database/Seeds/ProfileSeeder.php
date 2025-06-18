<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run()
    {
        $data = [
        [
            'user_id'        => 3,
            'address'        => 'Jl. Mawar No. 1',
            'gender'         => 'female',
            'religion'       => 'Islam',
            'parent_email'   => 'ortu3@example.com',
            'father_name'    => 'Budi',
            'mother_name'    => 'Ani',
            'nis'            => '10000001',
            'nisn'           => '20000001',
            'barcode_number' => 'BC0000001',
            'profile_photo'  => '',
            'created_by_id'  => 1,
            'created_at'     => date('Y-m-d H:i:s'),
        ],
        [
            'user_id'        => 4,
            'address'        => 'Jl. Melati No. 2',
            'gender'         => 'male',
            'religion'       => 'Islam',
            'parent_email'   => 'ortu4@example.com',
            'father_name'    => 'Slamet',
            'mother_name'    => 'Siti',
            'nis'            => '10000002',
            'nisn'           => '20000002',
            'barcode_number' => 'BC0000002',
            'profile_photo'  => '',
            'created_by_id'  => 1,
            'created_at'     => date('Y-m-d H:i:s'),
        ],
        [
            'user_id'        => 5,
            'address'        => 'Jl. Kenanga No. 3',
            'gender'         => 'female',
            'religion'       => 'Islam',
            'parent_email'   => 'ortu5@example.com',
            'father_name'    => 'Agus',
            'mother_name'    => 'Dewi',
            'nis'            => '10000003',
            'nisn'           => '20000003',
            'barcode_number' => 'BC0000003',
            'profile_photo'  => '',
            'created_by_id'  => 1,
            'created_at'     => date('Y-m-d H:i:s'),
        ],
        [
            'user_id'        => 6,
            'address'        => 'Jl. Anggrek No. 4',
            'gender'         => 'male',
            'religion'       => 'Islam',
            'parent_email'   => 'ortu6@example.com',
            'father_name'    => 'Rahmat',
            'mother_name'    => 'Dina',
            'nis'            => '10000004',
            'nisn'           => '20000004',
            'barcode_number' => 'BC0000004',
            'profile_photo'  => '',
            'created_by_id'  => 1,
            'created_at'     => date('Y-m-d H:i:s'),
        ],
        [
            'user_id'        => 7,
            'address'        => 'Jl. Dahlia No. 5',
            'gender'         => 'female',
            'religion'       => 'Islam',
            'parent_email'   => 'ortu7@example.com',
            'father_name'    => 'Hadi',
            'mother_name'    => 'Rina',
            'nis'            => '10000005',
            'nisn'           => '20000005',
            'barcode_number' => 'BC0000005',
            'profile_photo'  => '',
            'created_by_id'  => 1,
            'created_at'     => date('Y-m-d H:i:s'),
        ],
    ];


        // Insert multiple roles
        $this->db->table('profile')->insertBatch($data);
    }
}
