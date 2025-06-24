<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Helpers\AttendanceHelper;
use App\Models\AttendanceDailyEntryModel;
use App\Models\AttendanceModel;
use App\Models\ClassSemesterYearModel;
use App\Models\StudentClassSemesterModel;
use App\Models\UserRoleModel;

class Main extends BaseController
{
    public function index()
    {
        // $walas = session()->get('homeroom_teacher');

        $studentStatisticData = [
            'present' =>0,
            'late' => 0,
            'sick' => 0,
            'excused' => 0,
            'absent' => 0,
        ];
        
        $dailyEntryModel = new AttendanceDailyEntryModel();
        $todayEntries = $dailyEntryModel->getTodayEntries();
        $profileIds = array_column($todayEntries, 'profile_id');
        $attModel = new AttendanceModel();
        $todayAttendance = $attModel -> getTodayAttendanceList();

        $csyModel = new ClassSemesterYearModel();
        $csyData = $csyModel -> getInSession();
        
        $classList = [];
        foreach ($csyData as $entry) {
            $className = $entry['grade_name'].' '.$entry['class_code'];
            $classList[] = $className;
        }

        $studentIds = array_column($todayEntries,'student_id');
        $scsModel = new StudentClassSemesterModel();
        $scsList = $scsModel->getInsession($studentIds);
        $studentClass = [];
        foreach ($scsList as $entry) {
            $className = $entry['grade_name'].' '.$entry['class_code'];
            $studentClass[$entry['profile_id']] = $className;
        }
        
        $studentAttendanceToday = [];
        $attendancePerClass = [];
        foreach ($todayAttendance as $item) {
            $status = $item['attendance_type_id'];
            $profileId = $item['profile_id'];
            $studentClass =  $studentClass[$profileId] ?? '';

            if (!isset($studentAttendanceToday[$status])) {
                $studentAttendanceToday[$status] = [];
            }
            
            $studentAttendanceToday[$status][] = $profileId;
            
            if (isset($studentStatisticData[AttendanceHelper::ATTENDANCE_TYPE_MAPPING[$status]])) {
                $studentStatisticData[AttendanceHelper::ATTENDANCE_TYPE_MAPPING[$status]]++;
            }
            
            if (!$studentClass) {
                continue;
            }
            if (!isset($attendancePerClass[$studentClass])){
                $attendancePerClass[$studentClass] = [
                    'present' =>0,
                    'late' => 0,
                    'sick' => 0,
                    'excused' => 0,
                    'absent' => 0,
                ];
            }

            if (isset($attendancePerClass[$studentClass][AttendanceHelper::ATTENDANCE_TYPE_MAPPING[$status]])) {
                $attendancePerClass[$studentClass][AttendanceHelper::ATTENDANCE_TYPE_MAPPING[$status]]++;
            }
            
        }
        
        foreach ($todayEntries as $entry) {
            $profileId = $entry['profile_id'];
            $studentClass =  $studentClass[$profileId] ?? '';

            if (!isset($studentAttendance[$profileId])) {
                $entry['status'] = 'present';
                $studentStatisticData['present']++;
                if (!isset($attendancePerClass[$studentClass])){
                    $attendancePerClass[$studentClass] = [
                        'present' =>0,
                        'late' => 0,
                        'sick' => 0,
                        'excused' => 0,
                        'absent' => 0,
                    ];
                }
                $attendancePerClass[$studentClass]['present']++;
            }
        }

        return view('admin/home', [
            'attendance_data' => $studentStatisticData,
            'class_list' => $classList,
            'attendance_per_class' => $attendancePerClass,
            'viewing' => 'dashboard',
        ]);
    }
}
