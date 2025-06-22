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
use App\Models\StudentClassSemesterModel;

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

        $csyIds = array_column($csyList, 'id');
        $tcssHomeroomModel = new TeacherClassSemesterHomeroomModel();
        $form_teachers = $tcssHomeroomModel -> getFromCsyIds($csyIds);

        $form_teacher_data = [];
        foreach ($form_teachers as $form_teacher) {
            $csyId = $form_teacher['class_semester_year_id'];
            $teacherId = $form_teacher['teacher_id'];

            if (!isset($form_teacher_data[$csyId])){
                $form_teacher_data[$csyId] = [];
            }

            if (!isset($form_teacher_data[$csyId][$teacherId])){
                $form_teacher_data[$csyId][$teacherId] = [
                    'name' => $form_teacher['first_name'].' '.$form_teacher['last_name'],
                    'profile_photo' => $form_teacher['profile_photo']
                ];
            }
        }

        $scsModel = new StudentClassSemesterModel();
        $student_class_semesters = $scsModel-> getByCsyIds($csyIds);

        $studentTotalData = [];
        foreach ($student_class_semesters as $student_class_semester) {
            $csyId = $student_class_semester['class_semester_year_id'];
            $studentId = $student_class_semester['student_id'];
            
            if(!isset($studentTotalData[$csyId])){
                $studentTotalData[$csyId] = [];
            }
            
            $studentTotalData[$csyId][] = $studentId;
        }

        $semesterModel = new SemesterModel();
        $semesters = $semesterModel->getSemesters_from_academic_year_id($id);

        return view('admin/classes/class_semester_year', [
            'academic_year' => $academic_year,
            'class_semester_years' => $csyList,
            'student_data' => $studentTotalData,
            'form_teacher_data' => $form_teacher_data,
            'semesters' => $semesters,
            'viewing' => 'classes',
        ]);
    }

    public function create($academic_year_id)
    {
        $model = new AcademicYearModel();
        $classSemesterModel = new ClassSemesterModel();
        $semesterModel = new SemesterModel();

        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/'))->with('error', 'Data tidak ditemukan.');
        }

        $semesterModel = new SemesterModel();
        $semesters = $semesterModel->getSemesters_from_academic_year_id($academic_year_id);

        if ($this->request->getMethod() === 'POST'){
            $rules = [
                'code' => 'required',
            ];
            $data = $this->request->getPost();
            if ($this->validate($rules)) {

                $csyModel = new ClassSemesterYearModel();
                $csyId = $csyModel->insert([
                    'academic_year_id' => $academic_year_id,
                    'code' => $data['code'],
                    'created_by_id' => session()->get('user')['id'],
                    'grade_id' => $data['grade_id']
                ]);

                $classSemesterModel = new ClassSemesterModel();
                foreach ($semesters as $semester) {
                    $csId = $classSemesterModel-> insert([
                        'class_semester_year_id' => $csyId,
                        'semester_id' =>$semester['id'],
                        'created_by_id' => session()->get('user')['id']
                    ]);
                    
                    $homeroomModel = new TeacherClassSemesterHomeroomModel();
                    $homeroomModel -> insert([
                        'class_semester_id' => $csId,
                        'teacher_id' => $data['form_teacher'],
                        'created_by_id' => session()->get('user')['id']
                    ]);
                }
                return redirect()->to('admin/classes/academic-year/'.$academic_year['id'].'/')->with('success', 'Data Kelas berhasil ditambahkan.');
            }
        }

        $gradeModel = new GradeModel();
        $grades = $gradeModel -> withSection();
        $teacherModel = new TeacherModel();
        $teachers = $teacherModel -> getAllData();
        return view('admin/classes/create', [
            'academic_year' => $academic_year,
            'grades' => $grades,
            'semesters' => $semesters,
            'teachers' => $teachers,
            'viewing' => 'classes',
            'viewing_sub' => 'classes',
        ]);
    }
}
