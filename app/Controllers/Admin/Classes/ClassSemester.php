<?php

namespace App\Controllers\Admin\Classes;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\ClassSemesterModel;
use App\Models\ClassSemesterYearModel;
use App\Models\GradeModel;
use App\Models\ProfileModel;
use App\Models\SemesterModel;
use App\Models\StudentClassSemesterModel;
use App\Models\StudentModel;
use App\Models\TeacherClassSemesterHomeroomModel;
use App\Models\TeacherModel;

class ClassSemester extends BaseController
{
    public function detail($academic_year_id, $id){
        $model = new AcademicYearModel();

        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/'))->with('error', 'Data tidak ditemukan.');
        }

        $csyModel = new ClassSemesterYearModel();
        $class_semester_year = $csyModel-> getById($id);
        if (!$class_semester_year) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/'))->with('error', 'Data tidak ditemukan.');
        }

        $csModel = new ClassSemesterModel();
        $class_semesters = $csModel->getClassSemesterByClassSemesterYearId($id);
        $csIds = array_column($class_semesters, 'id');
        $homeroomModel = new TeacherClassSemesterHomeroomModel();
        $homeroomTeachers = $homeroomModel -> getFromClassSemesterIdsDistictTeacher($csIds);

        return view('admin/classes/class_semester/detail', [
            'academic_year' => $academic_year,
            'class_semester_year' => $class_semester_year,
            'class_homeroom' => $homeroomTeachers,
            'viewing' => 'classes',
            'viewing_sub' => 'classes',
        ]);
    }

    public function edit($academic_year_id, $id){
        $model = new AcademicYearModel();

        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/'))->with('error', 'Data tidak ditemukan.');
        }
        $csyModel = new ClassSemesterYearModel();
        $class_semester_year = $csyModel-> getById($id);
        if (!$class_semester_year) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/'))->with('error', 'Data tidak ditemukan.');
        }

        $csModel = new ClassSemesterModel();
        $class_semesters = $csModel->getCsByCsyId($id);
        $csIds = array_column($class_semesters, 'id');

        $teacherModel = new TeacherModel();
        $homeroomModel = new TeacherClassSemesterHomeroomModel();

        if ($this->request->getMethod() === 'POST'){
            $data = $this->request->getPost();
            
            $csyModel -> update($id, [
                'code' =>$data['code'],
                'updated_by_id' => session()->get('user')['id']
            ]);

            $homeroomData = $data['form_teacher'];

            foreach ($homeroomData as $class_semester_id => $teacher_id) {
                $existing = $homeroomModel-> getFromCsId($class_semester_id);

                if ($existing) {
                    // ada datanya
                    $homeroomModel -> update($existing['id'], [
                        'active' => 1,
                        'teacher_id' => $teacher_id,
                        'updated_by_id' => session()->get('user')['id']
                    ]);
                } else {
                    // tidak ada, insert baru
                    $homeroomModel->insert([
                        'teacher_id'        => $teacher_id,
                        'class_semester_id' => $class_semester_id,
                        'active'            => 1,
                        'created_by_id'     => session()->get('user')['id'],
                    ]);
                }
            }

            // cek data class_semesterId
            $semesterModel = new SemesterModel();
            $semesters = $semesterModel->getSemesters_from_academic_year_id($academic_year_id);

            foreach ($semesters as $semester) {
                $existingCs = $csModel->getBySemesterIdAndCsyId($semester['id'], $id);
                if (empty($existingCs)){
                    $csModel->insert([
                        'active' => 1,
                        'class_semester_year_id' => $id,
                        'created_by_id' => session()->get('user')['id'],
                        'semester_id' => $semester['id'],
                    ]);
                }
            }

            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/class_semester_year/'.$id.'/'))->with('success', 'Data berhasil diupdate.');
        }
        
        $teachers = $teacherModel -> getAllData();
        $homeroomTeachers = $homeroomModel -> getFromClassSemesterIds($csIds);

        $homeroomData = [];
        foreach ($homeroomTeachers as $form_teacher) {
            $homeroomData[$form_teacher['class_semester_id']] = $form_teacher['teacher_id'];
        }
        return view('admin/classes/class_semester/edit', [
            'academic_year' => $academic_year,
            'class_semesters' => $class_semesters,
            'class_semester_year' => $class_semester_year,
            'class_homeroom' => $homeroomData,
            'teachers' => $teachers,
            'viewing' => 'classes',
            'viewing_sub' => 'classes',
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
            'viewing_sub' => 'classes',
        ]);
    }

    public function class_hour($academic_year_id, $id){
        $model = new AcademicYearModel();
        $classSemesterModel = new ClassSemesterModel();
        $semesterModel = new SemesterModel();

        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/'))->with('error', 'Data tidak ditemukan.');
        }

        $csyModel = new ClassSemesterYearModel();
        $class_semester_year = $csyModel-> getById($id);
        if (!$class_semester_year) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $csModel = new ClassSemesterModel();
        $class_semesters = $csModel->getCsByCsyId($id);
        
        if ($this->request->getMethod() === 'POST'){
            $post = $this->request->getPost();

            $classSemesterData = [];

            foreach ($post['grace_period'] as $cs_id => $grace_period) {
                $classSemesterData[$cs_id]['grace_period'] = $grace_period;
            }

            foreach ($post['clock-in'] as $cs_id => $clock_in) {
                $classSemesterData[$cs_id]['clock_in'] = $clock_in;
            }

            foreach ($post['clock-out'] as $cs_id => $clock_out) {
                $classSemesterData[$cs_id]['clock_out'] = $clock_out;
            }


            foreach ($classSemesterData as $class_semesterId => $data) {
                $gracePeriod = !empty($data['grace_period']) ? $data['grace_period'] : null;
                $classSemesterModel ->update($class_semesterId,[
                    'grace_period' => $gracePeriod,
                    'clock_in' => $data['clock_in'],
                    'clock_out' => $data['clock_out'],
                    'updated_by_id' => session()->get('user')['id'],
                ]);
            }
            
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/class_semester_year/'.$id.'/class-hour/'))->with('success', 'Data berhasil diupdate.');
        }

        return view('admin/classes/class_semester/class_hour', [
            'academic_year' => $academic_year,
            'class_semesters' => $class_semesters,
            'class_semester_year' => $class_semester_year,
            'viewing' => 'classes',
            'viewing_sub' => 'classes',
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
