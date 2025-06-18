<?php

namespace App\Controllers\Admin\Classes;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\ClassSemesterModel;
use App\Models\GradeModel;
use App\Models\SemesterModel;
use App\Models\Student;
use App\Models\StudentClassSemesterModel;
use App\Models\StudentModel;
use App\Models\TeacherClassSemesterHomeroomModel;

class ClassSemester extends BaseController
{
    public function index($academic_year_id, $id)
    {
        $model = new AcademicYearModel();
        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id))->with('error', 'Data tidak ditemukan.');
        }
        $semesterModel = new SemesterModel();
        $semester = $semesterModel ->getSemesterById($id);
        if (!$semester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id))->with('error', 'Data tidak ditemukan.');
        }

        $classSemesterModel = new ClassSemesterModel();
        $classSemesters = $classSemesterModel->getClassSemesterBySemesterId($semester['id']);
        $classSemesterids = array_column($classSemesters, 'id');
        $homeroomModel = new TeacherClassSemesterHomeroomModel();
        $homeroomTeachers = $homeroomModel -> getFromClassSemesterIds($classSemesterids);
        
        $hashHomeroomTeacher = [];
        foreach ($homeroomTeachers as $homeroomTeacher) {
            $class_semester_id = $homeroomTeacher['class_semester_id'];
            $hashHomeroomTeacher[$class_semester_id][] = $homeroomTeacher;
        }
        return view('admin/classes/class_semester/index', [
            'academic_year' => $academic_year,
            'class_semesters' => $classSemesters,
            'class_homeroom' =>$hashHomeroomTeacher,
            'semester' => $semester,
            'viewing' => 'classes',
        ]);
    }

    public function create($academic_year_id, $id)
    {
        $model = new AcademicYearModel();
        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id))->with('error', 'Data tidak ditemukan.');
        }
        $semesterModel = new SemesterModel();
        $semester = $semesterModel ->getSemesterById($id);
        if (!$semester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id))->with('error', 'Data tidak ditemukan.');
        }

        if ($this->request->getMethod() === 'POST'){
            $rules = [
                'name' => 'required',
            ];
            $data = $this->request->getPost();
            if ($this->validate($rules)) {
                $classSemesterModel = new ClassSemesterModel();
                $classSemesterModel-> insert([
                    'name' => $data['name'],
                    'grade_id' => $data['grade_id'],
                    'semester_id' =>$semester['id']
                ]);
                return redirect()->to('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/')->with('success', 'Data Kelas berhasil ditambahkan.');
            }
        }

        $gradeModel = new GradeModel();
        $grades = $gradeModel -> withSection();
        return view('admin/classes/class_semester/create', [
            'academic_year' => $academic_year,
            'grades' => $grades,
            'semester' => $semester,
            'viewing' => 'classes',
        ]);
    }

    public function detail($academic_year_id, $semester_id, $id){
        $model = new AcademicYearModel();
        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id,'/semester/'.$semester_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        $semesterModel = new SemesterModel();
        $semester = $semesterModel ->getSemesterById($semester_id);
        if (!$semester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id,'/semester/'.$semester_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $classSemesterModel = new ClassSemesterModel();
        $classSemester = $classSemesterModel->getClassSemesterById($id);
        if (!$classSemester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id,'/semester/'.$semester_id.'/'))->with('error', 'Data tidak ditemukan.');
        }

        $homeroomModel = new TeacherClassSemesterHomeroomModel();
        $homeroomTeachers = $homeroomModel -> getFromClassSemesterId($classSemester['id']);
        return view('admin/classes/class_semester/detail', [
            'academic_year' => $academic_year,
            'class_semester' => $classSemester,
            'class_homeroom' => $homeroomTeachers,
            'semester' => $semester,
            'viewing' => 'classes',
        ]);
    }

    public function students($academic_year_id, $semester_id, $id){
        $model = new AcademicYearModel();
        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id,'/semester/'.$semester_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        $semesterModel = new SemesterModel();
        $semester = $semesterModel ->getSemesterById($semester_id);
        if (!$semester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id,'/semester/'.$semester_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $classSemesterModel = new ClassSemesterModel();
        $classSemester = $classSemesterModel->getClassSemesterById($id);
        if (!$classSemester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id,'/semester/'.$semester_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        if ($this->request->getMethod() === 'POST'){
            $data = $this->request->getPost();

            $classSemesterModel ->update($id,[
                'barcode' => $data['grace_period'],
                'updated_by_id' => session()->get('user')['id'],
            ]);
            
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/semester/'.$semester_id.'/class/'.$id.'/students/'))->with('success', 'Data berhasil diupdate.');
        }
        $scsModel = new StudentClassSemesterModel();
        $scs_list = $scsModel -> getByClassSemesterId($id);
        $studentIds = array_column($scs_list, 'student_id');

        $studentModel = new StudentModel();
        if (!empty($studentIds)) {
            $students = $studentModel->excludeStudentsIds($studentIds);
        } else {
            $students = $studentModel->getAllData();
        }

        return view('admin/classes/class_semester/students', [
            'academic_year' => $academic_year,
            'class_semester' => $classSemester,
            'semester' => $semester,
            'students' => $students,
            'student_class_semesters' => $scs_list,
            'viewing' => 'classes',
        ]);
    }

    public function class_hour($academic_year_id, $semester_id, $id){
        $model = new AcademicYearModel();
        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id,'/semester/'.$semester_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        $semesterModel = new SemesterModel();
        $semester = $semesterModel ->getSemesterById($semester_id);
        if (!$semester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id,'/semester/'.$semester_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $classSemesterModel = new ClassSemesterModel();
        $classSemester = $classSemesterModel->getClassSemesterById($id);
        if (!$classSemester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id,'/semester/'.$semester_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        if ($this->request->getMethod() === 'POST'){
            $data = $this->request->getPost();

            $classSemesterModel ->update($id,[
                'grace_period' => $data['grace_period'],
                'clock_in' => $data['clock-in'],
                'clock_out' => $data['clock-out'],
                'updated_by_id' => session()->get('user')['id'],
            ]);
            
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/semester/'.$semester_id.'/class/'.$id.'/class-hour/'))->with('success', 'Data berhasil diupdate.');
        }

        return view('admin/classes/class_semester/class_hour', [
            'academic_year' => $academic_year,
            'class_semester' => $classSemester,
            'semester' => $semester,
            'viewing' => 'classes',
        ]);
    }
}
