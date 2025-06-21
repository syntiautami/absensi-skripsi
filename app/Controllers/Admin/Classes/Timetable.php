<?php

namespace App\Controllers\Admin\Classes;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\ClassSemesterModel;
use App\Models\ClassSemesterSubjectModel;
use App\Models\ClassTimetablePeriodModel;
use App\Models\GradeModel;
use App\Models\ProfileModel;
use App\Models\SemesterModel;
use App\Models\StudentClassSemesterModel;
use App\Models\StudentModel;
use App\Models\SubjectModel;
use App\Models\TeacherClassSemesterHomeroomModel;
use App\Models\TeacherModel;
use App\Models\TimetablePeriodModel;

class Timetable extends BaseController
{
    public function index($academic_year_id, $semester_id, $id){
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
        return view('admin/classes/class_semester/timetable/index', [
            'academic_year' => $academic_year,
            'class_semester' => $classSemester,
            'class_homeroom' => $homeroomTeachers,
            'semester' => $semester,
            'viewing' => 'classes',
            'viewing_sub' => 'timetable',
        ]);
    }

    public function class_timetable_period($academic_year_id, $semester_id, $class_semesterId, $day){
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
        
        $classSemester = $classSemesterModel->getClassSemesterById($class_semesterId);
        if (!$classSemester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/semester/'.$semester_id.'/'))->with('error', 'Data tidak ditemukan.');
        }

        $homeroomModel = new TeacherClassSemesterHomeroomModel();
        $homeroomTeachers = $homeroomModel -> getFromClassSemesterId($classSemester['id']);

        $periodModel = new TimetablePeriodModel();
        $timetablePeriod = $periodModel->getTimetable($day);
        $timetableIds = array_column($timetablePeriod, 'id');
        
        $classSubjectModel = new ClassSemesterSubjectModel();
        $classSubjects = $classSubjectModel-> getAllSubjectByClassSemesterId($class_semesterId);
        
        $classTimetablePeriodModel = new ClassTimetablePeriodModel();

        if ($this->request->getMethod() == 'POST'){
            $data = $this->request->getPost('period');
            
            
            $today = date('Y-m-d');
            $existingClassTimetablePeriod = $classTimetablePeriodModel -> getClassTimetableByTimetableIds($timetableIds);
            $existingMap = [];
            foreach ($existingClassTimetablePeriod as $row) {
                $existingMap[$row['timetable_period_id']] = $row;
            }

            $insertBatch = [];
            $updateBatch = [];
            $toDeactivate = [];

            foreach ($timetableIds as $timetableId) {
                $classSemesterSubjectId = $data[$timetableId];

                if ($classSemesterSubjectId == '') {
                    continue;
                }

                if (isset($existingMap[$timetableId])) {
                    // Ada record → update kalau beda subject_id atau aktifkan
                    if ($existingMap[$timetableId]['class_semester_subject_id'] != $classSemesterSubjectId
                        || $existingMap[$timetableId]['active'] != 1) {

                        $updateBatch[] = [
                            'id' => $existingMap[$timetableId]['id'],
                            'class_semester_subject_id' => $classSemesterSubjectId,
                            'active' => 1,
                            'updated_by_id' => session()-> get('user')['id']
                        ];
                    }

                    // Hapus dari existingMap biar sisa yg nonaktif bisa dihandle
                    unset($existingMap[$timetableId]);

                } else {
                    $insertBatch[] = [
                        'timetable_period_id' => $timetableId,
                        'class_semester_subject_id' => $classSemesterSubjectId,
                        'active' => 1,
                        'created_by_id' => session()-> get('user')['id']
                    ];
                }
            }

            foreach ($existingMap as $row) {
                $toDeactivate[] = [
                    'id' => $row['id'],
                    'active' => 0,
                ];
            }

            if (!empty($insertBatch)) {
                $classTimetablePeriodModel->insertBatch($insertBatch);
            }

            if (!empty($updateBatch)) {
                $classTimetablePeriodModel->updateBatch($updateBatch, 'id');
            }

            if (!empty($toDeactivate)) {
                $classTimetablePeriodModel->updateBatch($toDeactivate, 'id');
            }

            return redirect()->to('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/'.$class_semesterId.'/timetable/'.$day.'/')->with('success', 'Data Jam Masuk berhasil diperbarui.');

        }

        $classTimetablePeriodList = $classTimetablePeriodModel->getActiveClassTimetableList($timetableIds);
        $existingClassSemesterSubjectData = [];
        foreach ($classTimetablePeriodList as $row) {
            $existingClassSemesterSubjectData[$row['timetable_period_id']] = $row['class_semester_subject_id'];
        }

        return view('admin/classes/class_semester/timetable/class_period', [
            'academic_year' => $academic_year,
            'class_semester' => $classSemester,
            'class_homeroom' => $homeroomTeachers,
            'existing_css_data' => $existingClassSemesterSubjectData,
            'day' => $day,
            'semester' => $semester,
            'subjects' => $classSubjects,
            'timetable_list' => $timetablePeriod,
            'viewing' => 'classes',
            'viewing_sub' => 'timetable',
        ]);
    }
}
