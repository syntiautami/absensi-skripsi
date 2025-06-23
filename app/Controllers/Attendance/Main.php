<?php

namespace App\Controllers\Attendance;

use App\Controllers\BaseController;
use App\Helpers\AttendanceHelper;
use App\Models\Attendance;
use App\Models\AttendanceDailyEntryModel;
use App\Models\AttendanceModel;
use App\Models\ProfileModel;
use App\Models\StudentClassSemesterModel;
use \CodeIgniter\I18n\Time;
use \Config\Services;
use Exception;

class Main extends BaseController
{
    public function index()
    {
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
        return view('attendance/index', [
            'date' => Time::now('Asia/Jakarta', 'en_ID')->getTimestamp(),
            'daily_entries' => $listStudentHome,
            'student_data' => $studentClass,
            'studentStatisticData' => $studentStatisticData,
            'present' => $totalEntries,
        ]);
    }

    public function tapping()
    {
        $profileModel = new ProfileModel();
        $attModel = new AttendanceModel();
        $BLOCKING_PERIOD = 15;
        
        $barcode = $this->request->getPost('barcode');
        
        $studentProfile = $profileModel->getByBarcodeNumber($barcode);
        if ($studentProfile) {
            $scsModel = new StudentClassSemesterModel();
            $studentData = $scsModel->getByStudentId($studentProfile['student_id']);

            $currentTap = date('H:i:s');
            $clockInTime = $studentData['clock_in']; // format: H:i:s
            $clockOutTime = $studentData['clock_out']; // format: H:i:s
            $gracePeriod = $studentData['grace_period']; // integer, dalam menit

            if (empty($clockInTime)) {
                return $this->response->setStatusCode(404)->setJSON([
                    'message' => 'Jam masuk belum diatur, silahkan hubungi guru piket.'
                ]);
            }

            $clockInTimestamp = strtotime($clockInTime);
            $gracePeriodTimestamp = $clockInTimestamp + ($gracePeriod * 60);
            $gracePeriodTime = date('H:i:s', $gracePeriodTimestamp);

            $blockingPeriodTimestamp = $clockInTimestamp + ($BLOCKING_PERIOD * 60);
            $blockingPeriodTime = date('H:i:s', $blockingPeriodTimestamp);

            
            $dailyEntryModel = new AttendanceDailyEntryModel();
            $todayEntry = $dailyEntryModel->getTodayEntry($studentProfile['id']);

            $existingAttendance = $attModel ->getTodayAttendance($studentData['id']);

            $attendanceStatus = null;
            if ($existingAttendance){
                $attType = (int)$existingAttendance['attendance_type_id'];
                if ($attType == 1){
                    $attendanceStatus = 'absent';
                }elseif ($attType == 2){
                    $attendanceStatus = 'sick';
                }elseif ($attType == 3){
                    $attendanceStatus = 'excused';
                }elseif ($attType == 4){
                    $attendanceStatus = 'late';
                }
            }
                
            
            $studentTappingTime = $currentTap;
            $sendEmail = true;
            if (!empty($todayEntry)) {
                $status = 'present';
                $sendEmail = false;
                if ($currentTap > $blockingPeriodTime) {
                    $status = 'home';
                    $sendEmail = true;
                    // tapping pulang
                    $dailyEntryModel->update($todayEntry['id'], [
                        'updated_by_id' => session()->get('user')['id'],
                        'clock_out' => date('Y-m-d H:i:s'),
                    ]);
                    $studentTappingTime = $currentTap;
                }else{
                    $studentTappingTime = $todayEntry['clock_in'];
                }
                if ($attendanceStatus && $attendanceStatus != 'late'){
                    $sendEmail = false;
                    $status = $attendanceStatus;
                }

            } else {
                // tapping masuk
                if($attendanceStatus && in_array($attendanceStatus,['sick','excused'])){
                    // sakit izin
                    $status = $attendanceStatus;
                    $sendEmail = false;
                }else{
                    $status = 'absent';
                    if ($currentTap < $clockInTime) {
                        $status = 'present';
                    } elseif (!empty($gracePeriod) && $currentTap >= $clockInTime && $currentTap <= $gracePeriodTime) {
                        $status = 'late';
                        if (!$attendanceStatus){
                            $attModel -> insert([
                                'student_class_semester_id' => $studentData['id'],
                                'attendance_type_id' => 4, //late
                                'date'       => date('Y-m-d'),
                                'created_by_id'  => session()->get('user')['id']
                            ]);
                        }
                    } else {
                        if (!$attendanceStatus) {
                            $attModel -> insert([
                                'student_class_semester_id' => $studentData['id'],
                                'attendance_type_id' => 1, //absent
                                'date'       => date('Y-m-d'),
                                'created_by_id'  => session()->get('user')['id']
                            ]);
                        }
                    }
                    // belum ada → insert baru
                    $dailyEntryModel->insert([
                        'profile_id'     => $studentProfile['id'],
                        'clock_in'       => date('Y-m-d H:i:s'),
                        'created_by_id'  => session()->get('user')['id'],
                    ]);
                }
            }

            $emailResults = [];
            if ($sendEmail && !empty($studentProfile['parent_email'])) {
                $dataSendEmail = [
                    'name' => $studentProfile['first_name'].' '.$studentProfile['last_name'],
                    'kelas' => $studentData['grade_name'].' '.$studentData['class_code'],
                    'parent_email' => $studentProfile['parent_email'],
                    'time' => date('H:i:s',strtotime($studentTappingTime)),
                    'status' => $status
                ];
                $emailResults = send_email($dataSendEmail);
            }
            return $this->response->setJSON([
                'data' => [
                    'id' => $studentData['id'],
                    'name' => $studentProfile['first_name'].' '.$studentProfile['last_name'],
                    'kelas' => $studentData['grade_name'].' '.$studentData['class_code'],
                    'img' => base_url(($studentProfile['profile_photo'] ?: 'assets/img/users/default.jpg')),
                    'timestamp' => $studentTappingTime,
                    'time' => date('H:i:s',strtotime($studentTappingTime)),
                    'status' => $status,
                    'blocking' => $blockingPeriodTime,
                    'email' =>$emailResults,
                ]
            ]);
        } 

        return $this->response->setStatusCode(404)->setJSON([
            'message' => 'Data siswa tidak ditemukan, silahkan hubungi guru piket.'
        ]);
    }
}
