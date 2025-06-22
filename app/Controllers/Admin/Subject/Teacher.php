<?php

namespace App\Controllers\Admin\Subject;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\ClassSemesterModel;
use App\Models\ClassSemesterSubjectModel;
use App\Models\ClassSemesterYearModel;
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

    public function classes($id){
        $model = new AcademicYearModel();
        $academicYear = $model ->getAcademicYearById($id);
        if (!$academicYear) {
            return redirect()->to(base_url('admin/subject/teacher/'))->with('error', 'Data tidak ditemukan.');
        }

        $csyModel = new ClassSemesterYearModel();
        $class_semester_years = $csyModel-> getClassSemesterYearByAcademicYearId($id);
        return view('admin/subject/teacher/classes', [
            'academic_year' =>$academicYear,
            'class_semester_years' => $class_semester_years,
            'viewing' => 'teacher-subject',
        ]);
    }

    public function teacher_subjects($academic_year_id, $id){
        $model = new AcademicYearModel();
        $academic_year = $model ->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/subject/teacher/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $csyModel = new ClassSemesterYearModel();
        $class_semester_year = $csyModel-> getById($id);
        if (!$class_semester_year) {
            return redirect()->to(base_url('admin/subject/class/academic-year/'.$academic_year_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $teacherModel = new TeacherModel();
        $teachercssModel = new TeacherClassSemesterSubjectModel();
        $cssModel = new ClassSemesterSubjectModel();
        $class_semester_subjects = $cssModel -> getActiveSubjectsByCsyId($id);

        $subjectsData = [];
        $classSemesterSubjectMapping = [];
        $cssIds = array_column($class_semester_subjects,'css_id');
        foreach ($class_semester_subjects as $class_semester_subject) {
            $subjectId = $class_semester_subject['subject_id'];
            $subject_name = $class_semester_subject['subject_name'];
            $csId = $class_semester_subject['class_semester_id'];

            if (!isset($subjectsData[$subjectId])) {
                $subjectsData[$subjectId] = [
                    'name' => $subject_name,
                    'id' =>$subjectId
                ];
            }

            if(!isset($classSemesterSubjectMapping[$csId])){
                $classSemesterSubjectMapping[$csId] = [];
            }

            if(!isset($classSemesterSubjectMapping[$csId][$subjectId])){
                $classSemesterSubjectMapping[$csId][$subjectId] = $class_semester_subject['css_id'];;
            }
        }

        $teacher_class_semester_subjects = $teachercssModel ->getExistingSubjectByCsyId($id);
        $existing_teacher_subjects = [];
        $existing_teacher_class_semester_subjects = [];

        foreach ($teacher_class_semester_subjects as $teacher_class_semester_subject) {
            $subjectId = $teacher_class_semester_subject['subject_id'];
            $teacherId = $teacher_class_semester_subject['teacher_id'];
            $cssId = $teacher_class_semester_subject['css_id'];
            $tcssId = $teacher_class_semester_subject['tcss_id'];

            if(!isset($existing_teacher_subjects[$teacherId])){
                $existing_teacher_subjects[$teacherId] = [];
                $existing_teacher_class_semester_subjects[$teacherId] = [];
            }

            if (!isset($existing_teacher_class_semester_subjects[$teacherId][$cssId])){
                $existing_teacher_class_semester_subjects[$teacherId][$cssId] = $tcssId;
            }
            $existing_teacher_subjects[$teacherId][] = $subjectId;
        }

        if ($this->request->getMethod() == 'POST'){
            $data = $this->request->getPost();
            
            $teachersSubjectData = $data['teachers'] ?? [];

            $csModel = new ClassSemesterModel();
            $class_semesters = $csModel->getCsByCsyId($id);

            $insertBatch = [];
            $updateBatch = [];
            $inactiveBatch = [];
            $userId = session()->get('user')['id'];
            if (!empty($teachersSubjectData)) {
                $checkedDataTeacherClassSemesterSubject = [];
                foreach ($teachersSubjectData as $teacherId => $subjectId) {
                    $existingSubjects = $existing_teacher_subjects[$teacherId] ?? [];

                    if (!isset($checkedDataTeacherClassSemesterSubject[$teacherId])) {
                        $checkedDataTeacherClassSemesterSubject[$teacherId] = [];
                    }
                    foreach ($class_semesters as $class_semester) {
                        $class_semester_id = $class_semester['id'];
                        
                        // Ambil class_semester_subject_id dari mapping
                        if (isset($classSemesterSubjectMapping[$class_semester_id][$subjectId])) {
                            $classSemesterSubjectId = $classSemesterSubjectMapping[$class_semester_id][$subjectId];

                            $checkedDataTeacherClassSemesterSubject[$teacherId][] = $classSemesterSubjectId;
                            // Cek apakah sudah ada di table
                            $existing = in_array($classSemesterSubjectId, $existingSubjects);
                            if ($existing) {
                                // Kalau ada → update
                                $updateBatch[] = [
                                    'id'               => $existing['id'],
                                    'active'           => 1,
                                ];
                            } else {
                                // Kalau belum ada → insert
                                $insertBatch[] = [
                                    'teacher_id'                 => $teacherId,
                                    'class_semester_subject_id'  => $classSemesterSubjectId,
                                    'active'                     => 1,
                                    'created_by_id'              => $userId,
                                ];
                            }
                        }
                    }
                }

                $inactiveBatch = [];
                foreach ($existing_teacher_class_semester_subjects as $teacherId => $classSemesterSubjectMapping) {
                    $postSubjects = $checkedDataTeacherClassSemesterSubject[$teacherId] ?? [];

                    foreach ($classSemesterSubjectMapping as $classSemesterSubjectId => $teacherClassSemesterSubjectId) {
                        if (!in_array($classSemesterSubjectId, $postSubjects)) {
                            // Tidak ada di POST → inactive
                            $inactiveBatch[] = [
                                'id'               => $teacherClassSemesterSubjectId,
                                'active'           => 0,
                            ];
                        }
                    }
                }

                if (!empty($inactiveBatch)) {
                    foreach ($inactiveBatch as $inactiveRow) {
                        $teachercssModel
                            ->where('id', $inactiveRow['id'])
                            ->set([
                                'active'         => 0,
                                'updated_by_id'  => $userId,
                            ])
                            ->update();
                    }
                }

                // Proses insert batch
                if (!empty($insertBatch)) {
                    $teachercssModel->insertBatch($insertBatch);
                }

                // Proses update
                if (!empty($updateBatch)) {
                    foreach ($updateBatch as $updateRow) {
                        $teachercssModel
                            ->where('id', $updateRow['id'])
                            ->set([
                                'active'         => 1,
                                'updated_by_id'  => $userId,
                            ])
                            ->update();
                    }
                }
            }else{
                // Jika tidak ada data mapping
                $teachercssModel
                    ->whereIn('class_semester_subject_id',$cssIds)
                    ->set([
                        'active' => 0,
                        'updated_by_id'  => $userId,
                    ])
                    ->update();
            }

            return redirect()->to(base_url('admin/subject/teacher/academic-year/'.$academic_year_id.'/class_semester_year/'.$id.'/'))->with('success', 'Data berhasil diubah.');
        }

        $teachers = $teacherModel-> getAllData();

        return view('admin/subject/teacher/teacher_subjects', [
            'academic_year' =>$academic_year,
            'class_semester_year' => $class_semester_year,
            'class_semester_subjects' => $class_semester_subjects,
            'existing_teacher_subjects' => $existing_teacher_subjects,
            'subjects_data' => $subjectsData,
            'teachers' => $teachers,
            'viewing' => 'teacher-subject',
        ]);
    }
}
