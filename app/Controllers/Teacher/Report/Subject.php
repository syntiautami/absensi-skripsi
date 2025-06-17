<?php

namespace App\Controllers\Teacher\Report;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Subject extends BaseController
{
    public function index()
    {
        return view('teacher/report/subject/index', [
            'viewing' => 'report-subject'
        ]);
    }
}
