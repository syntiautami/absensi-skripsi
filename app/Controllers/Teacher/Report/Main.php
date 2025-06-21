<?php

namespace App\Controllers\Teacher\Report;

use App\Controllers\BaseController;

class Main extends BaseController
{
    public function index()
    {
        $dateToday = date('Y-m-d');
        $walas = session()->get('homeroom_teacher');
        if (empty($walas)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('teacher/report/index', [
            'viewing' => 'report',
            '$walas' => $walas,
        ]);
    }

}
