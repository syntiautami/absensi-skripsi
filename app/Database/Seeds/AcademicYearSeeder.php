<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name'          => '2024-2025',
            'in_session'    => 1,
            'active'        => 1,
            'start_date'    => '2024-07-01 00:00:00',
            'end_date'      => '2025-06-30 23:59:59',
            'created_by_id' => 1, // isi sesuai user id yang ada
            'updated_by_id' => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        // Simple insert
        $this->db->table('academic_year')->insert($data);
    }
}
