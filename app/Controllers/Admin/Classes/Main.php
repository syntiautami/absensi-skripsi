<?php

namespace App\Controllers\Admin\Classes;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\ClassSemesterYearModel;
use App\Models\SemesterModel;

class Main extends BaseController
{
    public function index()
    {

        $model = new AcademicYearModel();
        $academic_years = $model->orderedAcademicYear();
        return view('admin/classes/index', [
            'academic_years' => $academic_years,
            'viewing' => 'classes',
        ]);
    }

    public function class_academic_year($id)
    {

        $model = new AcademicYearModel();
        $academic_year = $model->getAcademicYearById($id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/'))->with('error', 'Data tidak ditemukan.');
        }

        $csyModel = new ClassSemesterYearModel();
        $csyList = $csyModel-> getClassSemesterYearByAcademicYearId($id);

        return view('admin/classes/class_semester_year', [
            'academic_year' => $academic_year,
            'class_semester_years' => $csyList,
            'viewing' => 'classes',
        ]);
    }
}
