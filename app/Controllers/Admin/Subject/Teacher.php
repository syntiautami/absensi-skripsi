<?php

namespace App\Controllers\Admin\Subject;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Teacher extends BaseController
{
    public function index()
    {
        return view('admin/subject/teacher/index', [
            'viewing' => 'teacher-subject',
        ]);
    }
}
