<?php

namespace App\Controllers\Teacher\Report;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Subject extends BaseController
{
    public function index()
    {
        $dateToday = date('Y-m-d');
        $walas = session()->get('homeroom_teacher');
        if (empty($walas)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }


        return view('teacher/report/subject/index', [
            'viewing' => 'report',
            '$walas' => $walas,
        ]);
    }
}
