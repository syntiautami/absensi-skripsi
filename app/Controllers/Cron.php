<?php

namespace App\Controllers;

use App\Models\AttendanceDailyEntryModel;
use App\Models\AttendanceModel;
use App\Models\ClassSemesterModel;
use App\Models\StudentClassSemesterModel;
use CodeIgniter\Controller;
use DateTime;

class Cron extends Controller
{
    function roundTime($dt = null, $minutes = 15, $to = 'down')
    {
        if ($dt === null) {
            $dt = new DateTime();
        }

        $secondsInMinutes = 60;
        $roundToSeconds = $minutes * $secondsInMinutes;

        // Hitung detik sejak awal hari (00:00)
        $seconds = ($dt->format('H') * 3600) + ($dt->format('i') * 60) + $dt->format('s');

        if ($seconds % $roundToSeconds === 0 && (int)$dt->format('u') === 0) {
            $rounding = floor(($seconds + $roundToSeconds / 2) / $roundToSeconds) * $roundToSeconds;
        } else {
            if ($to === 'up') {
                $rounding = ceil(($seconds + $dt->format('u') / 1000000) / $roundToSeconds) * $roundToSeconds;
            } elseif ($to === 'down') {
                $rounding = floor($seconds / $roundToSeconds) * $roundToSeconds;
            } else {
                $rounding = floor(($seconds + $roundToSeconds / 2) / $roundToSeconds) * $roundToSeconds;
            }
        }

        // Hitung selisih detik untuk diadjust
        $adjustSeconds = $rounding - $seconds;

        $dt->modify("{$adjustSeconds} seconds");

        return $dt;
    }
    public function autoAlfa()
    {
        $csModel = new ClassSemesterModel();
        $datetimeNow = $this->roundTime(new DateTime(), 15, 'down');
        $todayDate = $datetimeNow->format('Y-m-d');
        $datetimeFull = $datetimeNow->format('Y-m-d H:i:s');

        // Buat searchTime = $datetimeNow - 15 menit
        $searchTimeObj = clone $datetimeNow;
        $searchTimeObj->modify('-15 minutes');
        $searchTime = $searchTimeObj->format('Y-m-d H:i:s');

        $class_semesters = $csModel-> getCronFunc($datetimeFull, $todayDate);
        $csIds = [];
        foreach ($class_semesters as $class_semester) {
            $gracePeriod = $class_semester['grace_period'];
            $clockInTime = $class_semester['clock_in'];
            
            $datePart = date('Y-m-d', strtotime($datetimeFull));
            $clockInTimeDate = $datePart . ' ' . $clockInTime;
            $clockInTimeDateObj = new DateTime($clockInTimeDate);
            if ($gracePeriod && $gracePeriod > 0) {
                $clockInTimeDateObj->modify("+{$gracePeriod} minutes");
            }
            $clockInTimeDate = $clockInTimeDateObj->format('Y-m-d H:i:s');

            if($searchTime < $clockInTimeDate && $datetimeFull >= $clockInTimeDate){
                $csIds[] = $class_semester['cs_id'];
            }
        }
        if (empty($csIds)) {
            return;
        }

        // jalanin cron
        $scsModel = new StudentClassSemesterModel();
        $scsList = $scsModel-> getByClassSemesterIds($csIds);
        $profileIds = array_column($scsList, 'profile_id');

        $attEntryModel = new AttendanceDailyEntryModel();
        $attEntryList = $attEntryModel-> cronHelper($profileIds, $todayDate);
        $existingAttendanceEntryProfileIds = [];
        if ($attEntryList) {
            $existingAttendanceEntryProfileIds = array_column($attEntryList, 'profile_id');
        }

        $attModel = new AttendanceModel();
        $attList = $attModel->cronFunc($csIds, $todayDate);
        $existingAttendanceScsId = [];
        if ($attList) {
            $existingAttendanceScsId = array_column($attList, 'student_class_semester_id');
        }

        $sendEmailData = [];
        $insertData = [];
        $emailRecipients = [];
        foreach ($scsList as $scs) {
            $profileId = $scs['profile_id'];
            $scsId = $scs['id'];

            $className = $scs['grade_name'].' '.$scs['class_code'];
            $full_name = $scs['first_name'].' '.$scs['last_name'];
            $parentEmail = $scs['parent_email'];

            if (in_array($profileId, $existingAttendanceEntryProfileIds)) {
                // student udah tapping
                continue;
            }
            
            if (in_array($scsId, $existingAttendanceScsId)) {
                // student udah ada attendance
                continue;
            }

            $sendEmailData[] = [
                'name' => $full_name,
                'kelas' => $className,
                'parent_email' => $parentEmail,
                'status' => 'auto-absent'
            ];

            // jadiin absent dan kirim blast
            $insertData[] = [
                'student_class_semester_id' => $scsId,
                'date'                      => $todayDate, // misal kamu punya $todayDate
                'attendance_type_id'        => 1, 
                'created_by_id'             => 1,
            ];

            $emailRecipients[] = [
                'email' => $parentEmail,
                'name'  => $full_name,
                'class' => $className
            ];
        }

        if (!empty($insertData)) {
            $attModel->insertBatch($insertData);
        }

        // send email later..
        echo 'Proses selesai.';

        foreach ($sendEmailData as $data) {
            send_email($data);
        }
    }
}
