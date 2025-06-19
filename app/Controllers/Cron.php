<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Cron extends Controller
{
    public function autoAlfa()
    {
        $db = \Config\Database::connect();

        // SET JAM BATAS - GANTI SESUAI SEKOLAH
        $jamBatas = '07:30:00';

        // Tanggal hari ini
        $tanggalHariIni = date('Y-m-d');

        // Ambil siswa yang hari ini belum tapping
        $builder = $db->table('attendance');
        $builder->select('id, siswa_id, status');
        $builder->where('tanggal', $tanggalHariIni);
        $builder->where('status', ''); // kosong berarti belum tapping
        $builder->where('CURRENT_TIME() >', $jamBatas, false);

        $results = $builder->get()->getResult();

        if (count($results) == 0) {
            echo 'Tidak ada siswa yang perlu diupdate ke Alfa.';
            return;
        }

        foreach ($results as $row) {
            $db->table('attendance')
                ->where('id', $row->id)
                ->update([
                    'status'    => 'Alfa',
                    'updated_at'=> date('Y-m-d H:i:s')
                ]);

            echo "Siswa ID {$row->siswa_id} di-set menjadi Alfa.<br>";
        }

        echo 'Proses selesai.';
    }
}
