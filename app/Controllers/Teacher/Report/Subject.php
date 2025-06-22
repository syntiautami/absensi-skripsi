<?php

namespace App\Controllers\Teacher\Report;

use App\Controllers\BaseController;
use App\Models\TeacherClassSemesterSubjectModel;
use CodeIgniter\HTTP\ResponseInterface;

class Subject extends BaseController
{
    public function index()
    {
        $dateToday = date('Y-m-d');

        return view('teacher/report/subject/index', [
            'viewing' => 'report',
        ]);
    }
}
