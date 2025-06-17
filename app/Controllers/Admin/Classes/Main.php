<?php

namespace App\Controllers\Admin\Classes;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
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
        $semesterModel = new SemesterModel();
        $semesters = $semesterModel ->getSemesters_from_academic_year_id($academic_year['id']);

        return view('admin/classes/semesters', [
            'academic_year' => $academic_year,
            'semesters' => $semesters,
            'viewing' => 'classes',
        ]);
    }
}
