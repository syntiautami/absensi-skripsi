<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Helpers\AttendanceHelper;
use App\Models\AttendanceDailyEntryModel;
use App\Models\AttendanceModel;
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
        $attModel = new AttendanceModel();
        $todayAttendance = $attModel -> getTodayAttendanceList();
        $scsModel = new StudentClassSemesterModel();

        $studentAttendanceToday = [];
        foreach ($todayAttendance as $item) {
            $status = $item['attendance_type_id'];
            $profileId = $item['profile_id'];

            if (!isset($studentAttendanceToday[$status])) {
                $studentAttendanceToday[$status] = [];
            }

            $studentAttendanceToday[$status][] = $profileId;

            if (isset($studentStatisticData[AttendanceHelper::ATTENDANCE_TYPE_MAPPING[$status]])) {
                $studentStatisticData[AttendanceHelper::ATTENDANCE_TYPE_MAPPING[$status]]++;
            }

        }
        foreach ($todayEntries as $entry) {
            if (!isset($studentAttendance[$entry['profile_id']])) {
                $entry['status'] = 'present';
                $studentStatisticData['present']++;
            }
        }
        
        return view('admin/home', [
            'attendance_data' => $studentStatisticData,
            'viewing' => 'dashboard',
        ]);
    }
}
