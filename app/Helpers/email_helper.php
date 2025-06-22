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
        $email->setFrom($fromEmail, $fromName);
        $email->setTo($data['parent_email']);
        $email->setSubject('Notifikasi Kehadiran Siswa - SMA IT Alia Tangerang');
        $email->setMessage($emailMsg);

        if ($email->send()) {
            return true; // berhasil
        }
        log_message('error', 'Email gagal: ' . print_r($email->printDebugger(['headers', 'subject', 'body']), true));
        return false; // gagal
    }
}
