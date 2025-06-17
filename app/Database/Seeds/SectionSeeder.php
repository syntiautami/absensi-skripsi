<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name'          => 'SMA',
            'active'        => 1,
            'created_by_id' => 1, // isi sesuai user id yang ada
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        // Simple insert
        $this->db->table('section')->insert($data);
    }
}
