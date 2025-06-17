<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'              => '1',
                'order'             => 1,
                'academic_year_id'  => 1,
                'in_session'        => 0,
                'start_date'        => '2024-07-01 00:00:00',
                'end_date'          => '2025-06-30 23:59:59',
                'created_by_id'     => 1, // isi sesuai user id yang ada
                'created_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'name'              => '2',
                'order'             => 2,
                'academic_year_id'  => 1,
                'in_session'        => 0,
                'start_date'        => '2024-07-01 00:00:00',
                'end_date'          => '2025-06-30 23:59:59',
                'created_by_id'     => 1, // isi sesuai user id yang ada
                'created_at'        => date('Y-m-d H:i:s'),
            ]
        ];

        // Simple insert
        $this->db->table('semester')->insertBatch($data);
    }
}
