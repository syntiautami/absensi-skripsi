<?php

namespace App\Controllers\Admin\Classes;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\ClassSemesterModel;
use App\Models\ClassSemesterSubjectModel;
use App\Models\ClassSemesterYearModel;
use App\Models\ClassTimetablePeriodModel;
use App\Models\SemesterModel;
use App\Models\TimetablePeriodModel;

class Timetable extends BaseController
{
    public function index($academic_year_id, $id){
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

        $semesterModel = new SemesterModel();
        $semesters = $semesterModel -> getSemesters_from_academic_year_id($academic_year_id);

        return view('admin/classes/class_semester/timetable/index', [
            'academic_year' => $academic_year,
            'class_semester_year' => $class_semester_year,
            'semesters' => $semesters,
            'viewing' => 'classes',
            'viewing_sub' => 'timetable',
        ]);
    }

    public function days($academic_year_id, $class_semester_year_id, $id){
        $model = new AcademicYearModel();

        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/'))->with('error', 'Data tidak ditemukan.');
        }

        $csyModel = new ClassSemesterYearModel();
        $class_semester_year = $csyModel-> getById($class_semester_year_id);
        if (!$class_semester_year) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $semesterModel = new SemesterModel();
        $semester = $semesterModel -> getSemesterById($id);
        
        if (!$semester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/class_semester_year/'.$class_semester_year_id.'/timetable/'))->with('error', 'Data tidak ditemukan.');
        }

        return view('admin/classes/class_semester/timetable/days', [
            'academic_year' => $academic_year,
            'class_semester_year' => $class_semester_year,
            'semester' => $semester,
            'viewing' => 'classes',
            'viewing_sub' => 'timetable',
        ]);
    }

    public function class_timetable_period($academic_year_id, $class_semester_year_id, $semester_id, $day){
        $model = new AcademicYearModel();
        $classSemesterModel = new ClassSemesterModel();
        $semesterModel = new SemesterModel();

        $academic_year = $model->getAcademicYearById($academic_year_id);
        if (!$academic_year) {
            return redirect()->to(base_url('admin/classes/'))->with('error', 'Data tidak ditemukan.');
        }
        $csyModel = new ClassSemesterYearModel();
        $class_semester_year = $csyModel-> getById($class_semester_year_id);
        if (!$class_semester_year) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $semesterModel = new SemesterModel();
        $semester = $semesterModel -> getSemesterById($semester_id);
        
        if (!$semester) {
            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/class_semester_year/'.$class_semester_year_id.'/timetable/'))->with('error', 'Data tidak ditemukan.');
        }

        $csModel = new ClassSemesterModel();
        $class_semester = $csModel-> getBySemesterIdAndCsyId($semester_id, $class_semester_year_id);

        $periodModel = new TimetablePeriodModel();
        $timetablePeriod = $periodModel->getTimetable($day);
        $timetableIds = array_column($timetablePeriod, 'id');
        
        $classSubjectModel = new ClassSemesterSubjectModel();
        $classSubjects = $classSubjectModel-> getAllSubjectByClassSemesterId($class_semester['id']);
        
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

            return redirect()->to(base_url('admin/classes/academic-year/'.$academic_year_id.'/class_semester_year/'.$class_semester_year_id.'/timetable/'.$semester_id.'/day/'.$day.'/'))->with('success', 'Data berhasil diperbarui.');
        }

        $classTimetablePeriodList = $classTimetablePeriodModel->getActiveClassTimetableList($timetableIds);
        $existingClassSemesterSubjectData = [];
        foreach ($classTimetablePeriodList as $row) {
            $existingClassSemesterSubjectData[$row['timetable_period_id']] = $row['class_semester_subject_id'];
        }

        return view('admin/classes/class_semester/timetable/class_period', [
            'academic_year' => $academic_year,
            'class_semester' => $class_semester,
            'class_semester_year' => $class_semester_year,
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
