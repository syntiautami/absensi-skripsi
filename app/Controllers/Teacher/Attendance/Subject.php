<?php

namespace App\Controllers\Teacher\Attendance;

use App\Controllers\BaseController;
use App\Models\AttendanceSubjectModel;
use App\Models\ClassTimetablePeriodModel;
use App\Models\SemesterModel;
use App\Models\StudentClassSemesterModel;
use App\Models\TeacherClassSemesterSubjectModel;
use App\Models\TeacherModel;
use DateTime;

class Subject extends BaseController
{
    function getSemesterWeeks($semesterStartDate, $semesterEndDate) {
        $startDate = new DateTime($semesterStartDate);
        $endDate = new DateTime($semesterEndDate);

        $diffDays = $startDate->diff($endDate)->days;

        return ceil(($diffDays + 1) / 7); // +1 biar hari pertama dihitung
    }

    function getCurrentWeek($semesterStartDate, $semesterEndDate) {
        $datesPerWeek = $this->getDatesPerWeek($semesterStartDate, $semesterEndDate);
        $today = (new DateTime())->format('Y-m-d');

        foreach ($datesPerWeek as $weekNumber => $weekDates) {
            if (in_array($today, $weekDates)) {
                return $weekNumber;
            }
        }

        // fallback kalau sudah lewat semester
        return count($datesPerWeek);
    }

    function getDatesPerWeek($semesterStartDate, $semesterEndDate) {
        $startDate = new DateTime($semesterStartDate);
        $endDate = new DateTime($semesterEndDate);

        // Geser ke hari Minggu sebelum startDate
        if ($startDate->format('N') != 1) {
            $startDate->modify('last monday');
        }

        $dates = [];
        $weekNum = 1;
        $currentDate = clone $startDate;

        while ($currentDate <= $endDate) {
            $weekDates = [];

            for ($i = 0; $i < 7; $i++) {
                if ($currentDate > $endDate) {
                    break;
                }
                $weekDates[] = $currentDate->format('Y-m-d');
                $currentDate->modify('+1 day');
            }

            $dates[$weekNum] = $weekDates;
            $weekNum++;
        }

        return $dates;
    }


    function getMappingWeekInfo($startDate, $endDate) {
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

    public function class_subject_attendance($ctpId, $year, $month, $day){
        $ctpModel = new ClassTimetablePeriodModel();
        $class_timetable_period = $ctpModel->getById($ctpId);

        if (!$class_timetable_period){
            return redirect()->to(base_url('teacher/attendance/subject/'))->with('error', 'Data tidak ditemukan.');
        }

        
        $scsModel = new StudentClassSemesterModel();
        $students = $scsModel -> getByClassSemesterId($class_timetable_period['cs_id']);
        $scsIds = array_column($students,'id');
        
        $date = new DateTime();
        $date->setDate($year, $month, $day);
        
        $selectedDate = $date;
        $tappingDate = $date->format('Y-m-d');
        
        $attSubjectModel = new AttendanceSubjectModel();
        $existing = $attSubjectModel-> getAttendanceSubjectExisting($scsIds, $ctpId ,$tappingDate);
        $studentAttendance = [];
        
        if (!empty($existing)) {
            foreach ($existing as $row) {
                $typeStr = '';
                switch ($row['attendance_type_id']) {
                    case 1:
                        $typeStr = 'absent';
                        break;
                    case 2:
                        $typeStr = 'sick';
                        break;
                    case 3:
                        $typeStr = 'excused';
                        break;
                    case 4:
                        $typeStr = 'late';
                        break;
                }
                $studentAttendance[$row['student_class_semester_id']] = $typeStr;
            }
        }

        if ($this->request->getMethod() == 'POST') {
            $existingIds = !empty($existing) ? array_column($existing, 'student_class_semester_id') : [];
            $existingMap = [];
            if (!empty($existing)) {
                foreach ($existing as $row) {
                    $existingMap[$row['student_class_semester_id']] = $row['id'];
                }
            }

            $studentAttendanceList = $this->request->getPost('absence_type');
            $result = array_filter($studentAttendanceList, function($value) {
                return $value !== '';
            });

            $dataInsert = [];
            $dataUpdate = [];
            $deleteIds = [];
            foreach ($result as $key => $value) {
                if ($value == 'present') {
                    // kalau ada existing dan dia 'present' → delete
                    if (in_array($key, $existingIds)) {
                        $deleteIds[] = $existingMap[$key];
                    }
                    continue; // lanjut next loop
                }
                $attendanceTypeId = ($value == 'absent') ? 1 : (($value == 'sick') ? 2 : (($value == 'excused') ? 3 : 4));
                $row = [
                    'student_class_semester_id' => $key,
                    'class_timetable_period_id' => $ctpId,
                    'attendance_type_id' => $attendanceTypeId,
                    'date' => $tappingDate,
                ];
                
                if (in_array($key, $existingIds)) {
                    // Untuk update
                    $row['updated_by_id'] = session()->get('user')['id'];
                    $row['id'] = $existingMap[$key];
                    $dataUpdate[] = $row;
                } else {
                    // Untuk insert
                    $row['created_by_id'] = session()->get('user')['id'];
                    $dataInsert[] = $row;
                }
            }
            if (!empty($dataUpdate)) {
                $attSubjectModel->updateBatch($dataUpdate, 'id');
            }

            if (!empty($dataInsert)) {
                $attSubjectModel->insertBatch($dataInsert);
            }

            if (!empty($deleteIds)) {
                $attSubjectModel->whereIn('id', $deleteIds)->delete();
            }

            return redirect()->to(base_url('teacher/attendance/subject/'.$ctpId.'/year/'.$year.'/month/'.$month.'/day/'.$day.'/'))->with('success', 'Data berhasil diupdate.');
        }

        return view('teacher/attendance/subject/attendance_subject', [
            'class_timetable_period' => $class_timetable_period,
            'date' => $tappingDate,
            'selected_date' => $selectedDate,
            'student_class_semesters' => $students,
            'studentAttendance' => $studentAttendance,
            'viewing' => 'attendance'
        ]);
    }
}
