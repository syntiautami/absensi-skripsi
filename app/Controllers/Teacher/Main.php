<?php

namespace App\Controllers\Teacher;

use App\Controllers\BaseController;
use App\Models\AttendanceDailyEntryModel;
use CodeIgniter\HTTP\ResponseInterface;

class Main extends BaseController
{
    public function index()
    {
        $dateToday = date('Y-m-d');
        $walas = session()->get('homeroom_teacher');

        $attendanceData = [];
        if ($walas) {
            $dailyEntryModel = new AttendanceDailyEntryModel();
            $todayEntries = $dailyEntryModel->getTodayEntries();
            $totalEntries = $dailyEntryModel->countTotalEntries();
            $attModel = new AttendanceModel();
            $todayAttendance = $attModel -> getTodayAttendanceList();
            
            $studentStatisticData = [
                'total' => 0,
                'present' =>0,
                'late' => 0,
                'absent' => 0,
            ];

            $studentAttendanceToday = [];
            $AttendancestudentLate = [];
            foreach ($todayAttendance as $item) {
                $status = $item['attendance_type_id'];
                $profileId = $item['profile_id'];

                if (!isset($studentAttendanceToday[$status])) {
                    $studentAttendanceToday[$status] = [];
                }

                $studentAttendanceToday[$status][] = $profileId;

                if ($status == 4) {
                    $AttendancestudentLate[] = $profileId;
                }

                if (isset($studentStatisticData[AttendanceHelper::ATTENDANCE_TYPE_MAPPING[$status]])) {
                    $studentStatisticData[AttendanceHelper::ATTENDANCE_TYPE_MAPPING[$status]]++;
                }
                $studentStatisticData['total']++;

            }
            $listStudentHome = [];
            foreach ($todayEntries as $entry) {
                if (!isset($studentAttendance[$entry['profile_id']])) {
                    $entry['status'] = 'present';
                    $studentStatisticData['present']++;
                    $studentStatisticData['total']++;
                }else if (in_array($entry['profile_id'], $AttendancestudentLate)){
                    $entry['status'] = 'late';
                }

                if ($entry['clock_out']) {
                    $entry['status'] = 'home';
                }
                $time = $entry['clock_out'] ? $entry['clock_out']: $entry['clock_in'];
                $listStudentHome[] = array_merge($entry, ['time' => $time]);
            }
            usort($listStudentHome, function ($a, $b) {
                return strtotime($b['time']) - strtotime($a['time']);
            });

            $listStudentHome = array_slice($listStudentHome, 0, 4);

            $studentIds = array_column($todayEntries,'student_id');
            $scsModel = new StudentClassSemesterModel();
            $scsList = [];
            if (!empty($studentIds)) {
                $scsList = $scsModel->getByStudentIds($studentIds);
            }
            $studentClass = [];
            foreach ($scsList as $entry) {
                $studentClass[$entry['profile_id']] = $entry;
            }
        }
        return view('teacher/home', [
            'attendance_data' => $attendanceData,
            'viewing' => 'dashboard'
        ]);
    }
}
