<?php

namespace App\Controllers\Teacher\Report;

use App\Controllers\BaseController;
use App\Models\ClassTimetablePeriodModel;
use App\Models\SemesterModel;
use App\Models\TeacherClassSemesterSubjectModel;
use App\Models\TeacherModel;

class Subject extends BaseController
{
    public function index()
    {
        helper('day');
        $profileId = session()->get('user')['profile_id'];
        $teacherModel = new TeacherModel();
        $teacher = $teacherModel->getDataByProfileId($profileId);

        $tcssModel = new TeacherClassSemesterSubjectModel();
        $teacher_class_semester_subjects = $tcssModel-> getInSessionTcssByTeacher($teacher['id']);
        
        $ctpData = [];
        $ctpCssIds = [];
        if (!empty($teacher_class_semester_subjects)) {
            $cssIds = array_column($teacher_class_semester_subjects, 'css_id');
            $semesterId = array_column($teacher_class_semester_subjects, 'semester_id')[0];
            $ctpModel = new ClassTimetablePeriodModel();
            $ctpList = $ctpModel-> getActiveByCssIds($cssIds);
    
            foreach ($ctpList as $ctp) {
                $cssId = $ctp['css_id'];
                $startTime = $ctp['start_time'];
                $endTime = $ctp['end_time'];
                $day = $ctp['day'];
                $ctpId = $ctp['ctp_id'];
    
                if (!isset($ctpData[$cssId])){
                    $ctpData[$cssId] = [];
                }
                
                if (!isset($ctpData[$cssId][$day])){
                    $ctpData[$cssId][$day] = [
                        'name' => day_indonesian($day),
                        'periods' => []
                    ];
    
                }
                $ctpData[$cssId][$day]['periods'][] = [
                    'id' => $ctpId,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ];
            }

            $ctpCssIds = array_keys($ctpData);
    
            $semesterModel = new SemesterModel();
            $semester = $semesterModel->getSemesterById($semesterId);

            $teacher_class_semester_subjects = array_filter($teacher_class_semester_subjects, function ($item) use ($ctpCssIds) {
                return in_array($item['css_id'], $ctpCssIds);
            });
            usort($teacher_class_semester_subjects, function($a, $b) {
                return strcmp($a['subject_name'], $b['subject_name']);
            });
        }

        return view('teacher/report/subject/index', [
            'teacher' => $teacher,
            'teacher_class_semester_subjects' => $teacher_class_semester_subjects,
            'viewing' => 'report',
        ]);
    }
}
