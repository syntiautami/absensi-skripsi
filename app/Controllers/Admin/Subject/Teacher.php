<?php

namespace App\Controllers\Admin\Subject;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\ClassSemesterModel;
use App\Models\GradeModel;
use App\Models\SemesterModel;
use App\Models\SubjectModel;
use App\Models\TeacherClassSemesterSubjectModel;
use App\Models\TeacherModel;

class Teacher extends BaseController
{
    public function index()
    {
        $model = new AcademicYearModel();
        $academicYears = $model->orderedAcademicYear();
        return view('admin/subject/teacher/index', [
            'academic_years' =>$academicYears,
            'viewing' => 'teacher-subject',
        ]);
    }

    public function teachers($id){
        $model = new AcademicYearModel();
        $academicYear = $model ->getAcademicYearById($id);
        if (!$academicYear) {
            return redirect()->to(base_url('admin/subject/teacher/'))->with('error', 'Data tidak ditemukan.');
        }

        $teacherModel = new TeacherModel();
        $teachers = $teacherModel-> getAllData();
        return view('admin/subject/teacher/teachers', [
            'academic_year' =>$academicYear,
            'teachers' => $teachers,
            'viewing' => 'teacher-subject',
        ]);
    }

    public function teacher_subjects($academicYearId, $id){
        $model = new AcademicYearModel();
        $academicYear = $model ->getAcademicYearById($academicYearId);
        if (!$academicYear) {
            return redirect()->to(base_url('admin/subject/teacher/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $teacherModel = new TeacherModel();
        $teacher = $teacherModel-> getDataById($id);
        
        if (!$teacher) {
            return redirect()->to(base_url('admin/subject/teacher/academic-year/'.$academicYearId.'/'))->with('error', 'Data tidak ditemukan.');
        }

        $teachercssModel = new TeacherClassSemesterSubjectModel();

        $csModel = new ClassSemesterModel();
        $classSemesters = $csModel->getPivotClassSemesterByAcademicYear($academicYearId);
        
        $subjectModel = new SubjectModel();
        $subjects = $subjectModel-> getAllData();

        $semesterModel = new SemesterModel();
        $semesters = $semesterModel-> getSemesters_from_academic_year_id($academicYearId);


        return view('admin/subject/teacher/teacher_subjects', [
            'academic_year' =>$academicYear,
            'classSemesters' => $classSemesters['tableData'],
            'semesters' => $classSemesters['semesterList'],
            'semesters' => $semesters,
            'subjects' => $subjects,
            'teacher' => $teacher,
            'viewing' => 'teacher-subject',
        ]);
    }
}
