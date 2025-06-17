<?php

namespace App\Controllers\Admin\AcademicYear;

use App\Controllers\BaseController;

use App\Models\AcademicYearModel;

class Main extends BaseController
{
    public function index()
    {
        $academicYearModel = new AcademicYearModel();
        $academicYears = $academicYearModel ->findAll();
        return view('admin/academic-year/index', [
            'academic_years' => $academicYears,
            'viewing' => 'academic-year',
        ]);
    }
}
