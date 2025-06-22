<?php

namespace App\Controllers\Teacher\Attendance;

use App\Controllers\Admin\AcademicYear\Semester;
use App\Controllers\BaseController;
use App\Models\ClassTimetablePeriodModel;
use App\Models\SemesterModel;
use App\Models\TeacherClassSemesterSubjectModel;
use App\Models\TeacherModel;
use CodeIgniter\HTTP\ResponseInterface;
use DateTime;

class Subject extends BaseController
{
    function getSemesterWeeks($semesterStartDate, $semesterEndDate) {
        $startDate = new DateTime($semesterStartDate);
        $endDate = new DateTime($semesterEndDate);

        $diffDays = $startDate->diff($endDate)->days;
        return ceil($diffDays / 7); // total weeks
    }

    function getCurrentWeek($semesterStartDate, $semesterEndDate) {
        $startDate = new DateTime($semesterStartDate);
        $today = new DateTime();

        if ($today < $startDate) {
            return 1;
        }

        if ($today > new DateTime($semesterEndDate)) {
            return $this->getSemesterWeeks($semesterStartDate, $semesterEndDate);
        }

        $diffDays = $startDate->diff($today)->days;
        return floor($diffDays / 7) + 1;
    }

    function getDatesPerWeek($semesterStartDate, $semesterEndDate) {
        $weeks = $this->getSemesterWeeks($semesterStartDate, $semesterEndDate);
        $startDate = new DateTime($semesterStartDate);

        $dates = [];
        for ($w = 1; $w <= $weeks; $w++) {
            $weekDates = [];

            // Set to Monday of current week
            $weekStart = clone $startDate;
            $weekStart->modify('Monday this week');
            $weekStart->modify('+' . (($w - 1) * 7) . ' days');

            for ($i = 0; $i < 7; $i++) {
                $date = clone $weekStart;
                $date->modify("+{$i} days");

                // Jika tanggal melewati semesterEndDate → stop
                if ($date > new DateTime($semesterEndDate)) {
                    break;
                }

                $weekDates[] = $date->format('Y-m-d');
            }

            $dates[$w] = $weekDates;
        }

        return $dates;
    }

    function getMappingWeekInfo($startDate, $endDate){
        $weeks = $this->getSemesterWeeks($startDate, $endDate);
        $currentWeek = $this->getCurrentWeek($startDate, $endDate);
        $datesPerWeek = $this->getDatesPerWeek($startDate, $endDate);

        return [
            'weeks' => $weeks,
            'current_week' => $currentWeek,
            'dates' => $datesPerWeek
        ];
    }

    public function index()
    {
        helper('day');
        $profileId = session()->get('user')['profile_id'];
        $teacherModel = new TeacherModel();
        $teacher = $teacherModel->getDataByProfileId($profileId);

        $tcssModel = new TeacherClassSemesterSubjectModel();
        $teacher_class_semester_subjects = $tcssModel-> getInSessionTcssByTeacher($teacher['id']);
        
        $ctpData = [];
        $weekInfo = [];
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
                $tpId = $ctp['timetable_period_id'];
    
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
                    'id' => $tpId,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ];
            }

            $ctpCssIds = array_keys($ctpData);
    
            $semesterModel = new SemesterModel();
            $semester = $semesterModel->getSemesterById($semesterId);
            $weekInfo = $this->getMappingWeekInfo($semester['start_date'], $semester['end_date']);

            $teacher_class_semester_subjects = array_filter($teacher_class_semester_subjects, function ($item) use ($ctpCssIds) {
                return in_array($item['css_id'], $ctpCssIds);
            });
            usort($teacher_class_semester_subjects, function($a, $b) {
                return strcmp($a['subject_name'], $b['subject_name']);
            });
        }

        return view('teacher/attendance/subject/index', [
            'ctpData' => $ctpData,
            'teacher' => $teacher,
            'teacher_class_semester_subjects' => $teacher_class_semester_subjects,
            'viewing' => 'attendance-subject',
            'weekInfo' => $weekInfo,
        ]);
    }

    public function class_subject_attendance($id, $day, $month, $year, $period){
        // class semester subject id
    }
}
