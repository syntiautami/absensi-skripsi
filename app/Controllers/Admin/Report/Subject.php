<?php

namespace App\Controllers\Admin\Report;

use App\Controllers\Admin\Classes\ClassSemester;
use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\ClassSemesterModel;
use App\Models\ClassSemesterSubjectModel;
use App\Models\ClassSemesterYearModel;
use App\Models\SubjectModel;
use CodeIgniter\HTTP\ResponseInterface;

class Subject extends BaseController
{
    public function index()
    {
        $model = new AcademicYearModel();
        $academicYears = $model->orderedAcademicYear();
        return view('admin/report/subject/index', [
            'academic_years' => $academicYears,
            'viewing' => 'report-subject',
        ]);
    }

    public function classes($id)
    {
        $academicYearModel = new AcademicYearModel();
        $academicYear = $academicYearModel->getAcademicYearById($id);
        if (!$academicYear) {
            return redirect()->to(base_url('admin/report/attendance/subject/'))->with('error', 'Data tidak ditemukan.');
        }

        $csyModel = new ClassSemesterYearModel();
        $class_semester_years = $csyModel->getClassSemesterYearByAcademicYearId($id);

        return view('admin/report/subject/classes', [
            'academic_year' => $academicYear,
            'class_semester_years' => $class_semester_years,
            'viewing' => 'report-subject',
        ]);
    }

    public function subject_list($academic_year_id, $id)
    {
        $academicYearModel = new AcademicYearModel();
        $academicYear = $academicYearModel->getAcademicYearById($academic_year_id);
        if (!$academicYear) {
            return redirect()->to(base_url('admin/report/attendance/subject/'))->with('error', 'Data tidak ditemukan.');
        }

        $csyModel = new ClassSemesterYearModel();
        $class_semester_year = $csyModel->getById($id);

        $csModel = new ClassSemesterModel();
        $class_semesters = $csModel->getCsByCsyId($id);

        $cssModel = new ClassSemesterSubjectModel();
        $class_semester_subjects = $cssModel->getActiveSubjectsByCsyId($id);

        $cssMapping = [];
        foreach ($class_semester_subjects as $row) {
            $classSemesterId = $row['class_semester_id'];
            $subjectId = $row['subject_id'];
            $cssId = $row['css_id'];

            if (!isset($cssMapping[$classSemesterId])) {
                $cssMapping[$classSemesterId] = [];
            }

            $cssMapping[$classSemesterId][$subjectId] = $cssId;
        }
        $subjectModel = new SubjectModel();
        $subjects = $subjectModel->getAllData();

        return view('admin/report/subject/subjects', [
            'academic_year' => $academicYear,
            'class_semesters' => $class_semesters,
            'class_semester_subject_data' => $cssMapping,
            'class_semester_year' => $class_semester_year,
            'subjects' => $subjects,
            'viewing' => 'report-subject',
        ]);
    }
}
