<?php

namespace App\Controllers\Teacher\Attendance;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Subject extends BaseController
{
    public function index()
    {
        return view('teacher/attendance/subject/index', [
            'viewing' => 'attendance-subject'
        ]);
    }
}
