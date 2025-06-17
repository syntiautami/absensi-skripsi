<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'              => '10',
                'order'             => 1,
                'section_id'        => 2,
                'created_by_id'     => 1, // isi sesuai user id yang ada
                'created_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'name'              => '11',
                'order'             => 2,
                'section_id'        => 2,
                'created_by_id'     => 1, // isi sesuai user id yang ada
                'created_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'name'              => '12',
                'order'             => 3,
                'section_id'        => 2,
                'created_by_id'     => 1, // isi sesuai user id yang ada
                'created_at'        => date('Y-m-d H:i:s'),
            ],
            
        ];

        // Simple insert
        $this->db->table('grade')->insertBatch($data);
    }
}
