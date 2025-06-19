<?php

namespace App\Controllers\Teacher\Attendance;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\StudentClassSemesterModel;

class Main extends BaseController
{
    public function index()
    {

        $walas = session()->get('homeroom_teacher');
        $scsModel = new StudentClassSemesterModel();
        $students = $scsModel -> getByClassSemesterId($walas['class_semester_id']);

        return view('teacher/attendance/index', [
            'date' => date('d-M-Y'),
            'student_class_semesters' => $students,
            'viewing' => 'attendance'
        ]);
    }
}
