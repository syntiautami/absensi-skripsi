<?php

namespace App\Controllers\Admin\Classes;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\ClassSemesterModel;
use App\Models\GradeModel;
use App\Models\ProfileModel;
use App\Models\SemesterModel;
use App\Models\StudentClassSemesterModel;
use App\Models\StudentModel;
use App\Models\TeacherClassSemesterHomeroomModel;
use App\Models\TeacherModel;

class ClassSemester extends BaseController
{
    public function index($academic_year_id, $id)
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
        ]);
    }

    public function detail($academic_year_id, $semester_id, $id){
        $model = new AcademicYearModel();
        $classSemesterModel = new ClassSemesterModel();
        $semesterModel = new SemesterModel();

        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/'))->with('error', 'Data tidak ditemukan.');
        }
        $semester = $semesterModel ->getSemesterById($semester_id);
        if (!$semester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $classSemester = $classSemesterModel->getClassSemesterById($id);
        if (!$classSemester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/semester/'.$semester_id.'/'))->with('error', 'Data tidak ditemukan.');
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

    public function edit($academic_year_id, $semester_id, $id){
        $model = new AcademicYearModel();
        $classSemesterModel = new ClassSemesterModel();
        $semesterModel = new SemesterModel();

        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/'))->with('error', 'Data tidak ditemukan.');
        }
        $semester = $semesterModel ->getSemesterById($semester_id);
        if (!$semester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $classSemester = $classSemesterModel->getClassSemesterById($id);
        if (!$classSemester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/semester/'.$semester_id.'/'))->with('error', 'Data tidak ditemukan.');
        }

        $teacherModel = new TeacherModel();
        $homeroomModel = new TeacherClassSemesterHomeroomModel();

        if ($this->request->getMethod() === 'POST'){
            $data = $this->request->getPost();
            
            $classSemesterModel -> update($id, [
                'name' =>$data['name'],
                'updated_by_id' => session()->get('user')['id']
            ]);

            $homeroomModel -> where('class_semester_id',$id)->set([
                'teacher_id' =>$data['form_teacher'],
                'updated_by_id' => session()->get('user')['id']
            ])->update();

            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/semester/'.$semester_id.'/class/'.$id.'/'))->with('success', 'Data berhasil diupdate.');
        }
        $teachers = $teacherModel -> getAllData();
        $homeroomTeachers = $homeroomModel -> getFromClassSemesterId($classSemester['id']);
        $homeroomTeachers = array_column($homeroomTeachers, 'teacher_id');
        return view('admin/classes/class_semester/edit', [
            'academic_year' => $academic_year,
            'class_semester' => $classSemester,
            'class_homeroom' => $homeroomTeachers,
            'semester' => $semester,
            'teachers' => $teachers,
            'viewing' => 'classes',
        ]);
    }
    
    public function students($academic_year_id, $semester_id, $id){
        $model = new AcademicYearModel();
        $classSemesterModel = new ClassSemesterModel();
        $scsModel = new StudentClassSemesterModel();
        $studentModel = new StudentModel();
        $semesterModel = new SemesterModel();

        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/'))->with('error', 'Data tidak ditemukan.');
        }
        $semester = $semesterModel ->getSemesterById($semester_id);
        if (!$semester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $classSemester = $classSemesterModel->getClassSemesterById($id);
        if (!$classSemester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/semester/'.$semester_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        if ($this->request->getMethod() === 'POST'){
            $barcodeNumbers = $this->request->getPost('barcode_number');
            $newStudents = $this->request->getPost('students');

            if (!empty($newStudents)) {
                foreach ($newStudents as $student_id) {
                    // Cek apakah sudah ada di student_class_semester
                    $existing = $scsModel
                        ->where('student_id', $student_id)
                        ->where('class_semester_id', $id)
                        ->first();

                    if ($existing) {
                        if (!$existing['active']) {
                            // Kalau sudah ada tapi inactive, aktifkan
                            $scsModel->update($existing['id'], [
                                'active' => true,
                                'updated_by_id' => session()->get('user')['id'],
                            ]);
                        }
                        // Kalau sudah active, skip saja
                    } else {
                        // Kalau belum ada, insert baru
                        $scsModel->insert([
                            'student_id' => $student_id,
                            'class_semester_id' => $id,
                            'active' => true,
                            'created_by_id' => session()->get('user')['id'],
                        ]);
                    }
                }
            }
            
            $pModel = new ProfileModel();
            if (!empty($barcodeNumbers)) {
                foreach ($barcodeNumbers as $profile_id => $barcode) {
                    $pModel->update($profile_id, [
                        'barcode_number' => $barcode,
                        'updated_by_id' => session()->get('user')['id']
                    ]);
                }
            }
                
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/semester/'.$semester_id.'/class/'.$id.'/students/'))->with('success', 'Data berhasil diupdate.');
        }
        
        $scs_list = $scsModel -> getByClassSemesterId($id);
        $studentIds = array_column($scs_list, 'student_id');

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
        $classSemesterModel = new ClassSemesterModel();
        $semesterModel = new SemesterModel();

        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/'))->with('error', 'Data tidak ditemukan.');
        }
        $semester = $semesterModel ->getSemesterById($semester_id);
        if (!$semester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $classSemester = $classSemesterModel->getClassSemesterById($id);
        if (!$classSemester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/semester/'.$semester_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        if ($this->request->getMethod() === 'POST'){
            $data = $this->request->getPost();
            $gracePeriod = !empty($data['grace_period']) ? $data['grace_period'] : null;
            $classSemesterModel ->update($id,[
                'grace_period' => $gracePeriod,
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

    // post
    public function delete($academic_year_id, $semester_id, $class_semester_id, $id){
        $model = new AcademicYearModel();
        $classSemesterModel = new ClassSemesterModel();
        $scsModel = new StudentClassSemesterModel();
        $semesterModel = new SemesterModel();

        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/'))->with('error', 'Data tidak ditemukan.');
        }
        $semester = $semesterModel ->getSemesterById($semester_id);
        if (!$semester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $classSemester = $classSemesterModel->getClassSemesterById($class_semester_id);
        if (!$classSemester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/semester/'.$semester_id.'/'))->with('error', 'Data tidak ditemukan.');
        }

        $studentClassSemester = $scsModel -> getById($id);
        if (!$studentClassSemester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/semester/'.$semester_id.'/class/'.$class_semester_id.'/students/'))->with('error', 'Data tidak ditemukan.');
        }

        $scsModel -> update(
            $id,
            [
                'active' => 0,
                'updated_by_id' => session()->get('user')['id'],
            ]
        );
        
        return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/semester/'.$semester_id.'/class/'.$class_semester_id.'/students/'))->with('success', 'Data berhasil diupdate.');
    }
}
