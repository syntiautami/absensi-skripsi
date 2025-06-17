<?php

namespace App\Controllers\Admin\Classes;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\ClassSemesterModel;
use App\Models\SemesterModel;

class ClassSemester extends BaseController
{
    public function index($academic_year_id, $id)
    {
        $model = new AcademicYearModel();
        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/'))->with('error', 'Data tidak ditemukan.');
        }
        $semesterModel = new SemesterModel();
        $semester = $semesterModel ->getSemesterById($id);
        if (!$semester) {
            return redirect()->to(base_url('admin/classes/'))->with('error', 'Data tidak ditemukan.');
        }

        $classSemesterModel = new ClassSemesterModel();
        $classSemesters = $classSemesterModel->getClassSemesterBySemesterId($semester['id']);

        return view('admin/classes/class_semester/index', [
            'academic_year' => $academic_year,
            'class_semesters' => $classSemesters,
            'semester' => $semester,
            'viewing' => 'classes',
        ]);
    }
}
