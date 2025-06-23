<?php

if (!function_exists('send_email')) {
    function send_email($data=[])
    {
        $email = \Config\Services::email();

        // ambil fromEmail & fromName dari .env / Config\Email
        $fromEmail = $email->fromEmail;
        $fromName = $email->fromName;

        $homeMsg = "
            Kami informasikan bahwa anak Anda:
            <br><br>
            <b>Nama Siswa:</b> {$data['name']}<br>
            <b>Kelas:</b> {$data['kelas']}<br>
            <b>Telah meninggalkan sekolah pada pukul:</b> {$data['time']}<br><br>

            Terima kasih atas perhatian Bapak/Ibu.  
            Salam hormat,  
            <b>Sistem Absensi SMA IT Alia Tangerang</b>
        ";

        $lateMsg = "
            Kami informasikan bahwa anak Anda:
            <br><br>
            <b>Nama Siswa:</b> {$data['name']}<br>
            <b>Kelas:</b> {$data['kelas']}<br>
            <b>Telah meninggalkan sekolah pada pukul:</b> {$data['time']} dengan status terlambat.<br><br>

            Terima kasih atas perhatian Bapak/Ibu.  
            Salam hormat,  
            <b>Sistem Absensi SMA IT Alia Tangerang</b>
        ";

        $presentMsg = "
            Yth. Bapak/Ibu Wali Murid,<br><br>
            Kami informasikan bahwa anak Anda:
            <br><br>
            <b>Nama Siswa:</b> {$data['name']}<br>
            <b>Kelas:</b> {$data['kelas']}<br>
            <b>Telah hadir di sekolah hari ini pada pukul:</b> {$data['time']}<br><br>

            Terima kasih atas perhatian Bapak/Ibu.  
            Salam hormat,  
            <b>Sistem Absensi SMA IT Alia Tangerang</b>
        ";

        $absentMsg = "
            Yth. Bapak/Ibu Wali Murid,<br><br>
            Kami informasikan bahwa anak Anda:
            <br><br>
            <b>Nama Siswa:</b> {$data['name']}<br>
            <b>Kelas:</b> {$data['kelas']}<br>
            <b>Telah hadir di sekolah hari ini pada pukul:</b> {$data['time']}, melewati batas toleransi keterlambatan.<br><br>

            Terima kasih atas perhatian Bapak/Ibu.  
            Salam hormat,  
            <b>Sistem Absensi SMA IT Alia Tangerang</b>
        ";

        $emailMsg = $presentMsg;
        if ($data['status'] == 'home'){
            $emailMsg = $homeMsg;
        }elseif($data['status'] == 'absent'){
            $emailMsg = $absentMsg;
        }elseif($data['status'] == 'late'){
            $emailMsg = $lateMsg;
        }
        try {
            $email->setFrom($fromEmail, $fromName);
            $email->setTo($data['parent_email']);
            $email->setSubject('Notifikasi Kehadiran Siswa - SMA IT Alia Tangerang');
            $email->setMessage($emailMsg);

            if ($email->send()) {
                return [
                    'success' => true,
                    'message' => 'Email berhasil dikirim.'
                ];
            } else {
                $debugInfo = print_r($email->printDebugger(['headers', 'subject', 'body']), true);
                log_message('error', 'Email gagal: ' . $debugInfo);

                return [
                    'success' => false,
                    'message' => 'Gagal mengirim email.',
                    'debug'   => $debugInfo
                ];
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception saat kirim email: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }
}
