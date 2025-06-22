<?php

namespace App\Controllers\Admin\Classes;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\ClassSemesterModel;
use App\Models\ClassSemesterYearModel;
use App\Models\GradeModel;
use App\Models\TeacherModel;
use App\Models\TeacherClassSemesterHomeroomModel;
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

    public function create($academic_year_id, $id)
    {
        $model = new AcademicYearModel();
        $classSemesterModel = new ClassSemesterModel();
        $semesterModel = new SemesterModel();

        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/'))->with('error', 'Data tidak ditemukan.');
        }
        $semester = $semesterModel ->getSemesterById($id);
        if (!$semester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/'))->with('error', 'Data tidak ditemukan.');
        }

        if ($this->request->getMethod() === 'POST'){
            $rules = [
                'name' => 'required',
            ];
            $data = $this->request->getPost();
            if ($this->validate($rules)) {
                $classSemesterModel = new ClassSemesterModel();
                $csId = $classSemesterModel-> insert([
                    'name' => $data['name'],
                    'grade_id' => $data['grade_id'],
                    'semester_id' =>$semester['id'],
                    'created_by_id' => session()->get('user')['id']
                ]);
                
                $homeroomModel = new TeacherClassSemesterHomeroomModel();
                $homeroomModel -> insert([
                    'class_semester_id' => $csId,
                    'teacher_id' => $data['form_teacher'],
                    'created_by_id' => session()->get('user')['id']
                ]);
                return redirect()->to('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/')->with('success', 'Data Kelas berhasil ditambahkan.');
            }
        }

        $gradeModel = new GradeModel();
        $grades = $gradeModel -> withSection();
        $teacherModel = new TeacherModel();
        $teachers = $teacherModel -> getAllData();
        return view('admin/classes/class_semester/create', [
            'academic_year' => $academic_year,
            'grades' => $grades,
            'semester' => $semester,
            'teachers' => $teachers,
            'viewing' => 'classes',
            'viewing_sub' => 'classes',
        ]);
    }
}
