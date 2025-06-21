<?php

namespace App\Controllers\Admin\Report;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\ClassSemesterModel;
use App\Models\GradeModel;
use App\Models\SemesterModel;

class Main extends BaseController
{
    public function index()
    {
        $model = new AcademicYearModel();
        $academicYears = $model->orderedAcademicYear();
        return view('admin/report/index', [
            'academic_years' => $academicYears,
            'viewing' => 'report',
        ]);
    }

    public function grades($id){
        $academicYearModel = new AcademicYearModel();
        $academicYear = $academicYearModel ->getAcademicYearById($id);
        if (!$academicYear) {
            return redirect()->to(base_url('admin/report/attendance/'))->with('error', 'Data tidak ditemukan.');
        }

        $csModel = new ClassSemesterModel();
        $classSemesters = $csModel->getPivotClassSemesterByAcademicYear($id);

        return view('admin/report/grades', [
            'academic_year' => $academicYear,
            'classSemesters' => $classSemesters['tableData'],
            'semesters' => $classSemesters['semesterList'],
            'viewing' => 'report',
        ]);
    }
}
