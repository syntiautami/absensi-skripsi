<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\TeacherClassSemesterHomeroomModel;

class WalasFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = session()->get('user'); // Ambil user yang login
        $role = session()->get('role');

        // Hanya jalankan filter kalau role = guru
        if ($user && $role === 'teacher') {

            // Cek apakah guru ini wali kelas
            $model = new TeacherClassSemesterHomeroomModel();
            $waliKelasData = $model->getByProfileId($user['profile_id']);
            session()->set('homeroom_teacher',$waliKelasData);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosong, ga perlu after
    }
}
