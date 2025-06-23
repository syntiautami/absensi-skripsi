<?php

namespace App\Controllers\Teacher;

use App\Controllers\BaseController;
use App\Helpers\AttendanceHelper;
use App\Models\AttendanceDailyEntryModel;
use App\Models\AttendanceModel;
use App\Models\ProfileModel;
use App\Models\StudentClassSemesterModel;
use CodeIgniter\HTTP\ResponseInterface;

class Main extends BaseController
{
    public function index()
    {
        $walas = session()->get('homeroom_teacher');

        $studentStatisticData = [
            'present' =>0,
            'late' => 0,
            'sick' => 0,
            'excused' => 0,
            'absent' => 0,
        ];
        if ($walas) {
            $csId = $walas['class_semester_id'];
            $scsModel = new StudentClassSemesterModel();
            $scsData = $scsModel->getByClassSemesterId($csId);
            $profileIds = array_column($scsData, 'profile_id');
            $scsIds = array_column($scsData, 'id');

            $dailyEntryModel = new AttendanceDailyEntryModel();
            $todayEntries = $dailyEntryModel->getTodayEntriesByProfileIds($profileIds);
            $attModel = new AttendanceModel();
            $todayAttendance = $attModel -> getTodayAttendanceByscsId($scsIds);

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
                // $studentStatisticData['total']++;

            }
            foreach ($todayEntries as $entry) {
                if (!isset($studentAttendance[$entry['profile_id']])) {
                    $entry['status'] = 'present';
                    $studentStatisticData['present']++;
                    // $studentStatisticData['total']++;
                }
            }
        }
        return view('teacher/home', [
            'attendance_data' => $studentStatisticData,
            'viewing' => 'dashboard',
            'walas' => $walas,
        ]);
    }
}
