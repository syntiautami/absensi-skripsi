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
            <b>Telah meninggalkan sekolah pada pukul:</b> {$data['timestamp']}<br><br>

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
            <b>Telah hadir di sekolah pada pukul:</b> {$data['timestamp']}<br><br>

            Terima kasih atas perhatian Bapak/Ibu.  
            Salam hormat,  
            <b>Sistem Absensi SMA IT Alia Tangerang</b>
        ";

        $emailMsg = $data['status'] == 'home' ? $homeMsg : $presentMsg;
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
