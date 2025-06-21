<?php

namespace App\Controllers\Admin\Subject;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use CodeIgniter\HTTP\ResponseInterface;

class Classes extends BaseController
{
    public function index()
    {
        $model = new AcademicYearModel();
        $academicYears = $model->orderedAcademicYear();
        return view('admin/subject/classes/index', [
            'academic_years' => $academicYears,
            'viewing' => 'class-subject',
        ]);
    }
}
