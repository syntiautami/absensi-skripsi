<?php

namespace App\Controllers\Admin\Report;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\ClassSemesterModel;
use App\Models\ClassSemesterYearModel;
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

        $csyModel = new ClassSemesterYearModel();
        $class_semester_years = $csyModel->getClassSemesterYearByAcademicYearId($id);
        $csyIds = array_column($class_semester_years, 'id');


        $csModel = new ClassSemesterModel();
        $class_semesters = $csModel->getCsByCsyIds($csyIds);

        $class_semester_data = [];
        foreach ($class_semesters as $class_semester) {
            $csyId = $class_semester['csy_id'];
            $semesterId = $class_semester['semester_id'];

            if (!isset($class_semester_data[$csyId])) {
                $class_semester_data[$csyId] = [];
            }

            if (!isset($class_semester_data[$csyId][$semesterId])){
                $class_semester_data[$csyId][$semesterId] = $class_semester['cs_id'];
            }
        }

        $semesterModel = new SemesterModel();
        $semesters = $semesterModel->getSemesters_from_academic_year_id($id);

        return view('admin/report/grades', [
            'academic_year' => $academicYear,
            'semesters' => $semesters,
            'class_semester_data' => $class_semester_data,
            'class_semester_years' => $class_semester_years,
            'viewing' => 'report',
        ]);
    }
}
