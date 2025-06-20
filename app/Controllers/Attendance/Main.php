<?php

namespace App\Controllers\Attendance;

use App\Controllers\BaseController;
use App\Models\Attendance;
use App\Models\AttendanceDailyEntryModel;
use App\Models\AttendanceModel;
use App\Models\ProfileModel;
use App\Models\StudentClassSemesterModel;
use \CodeIgniter\I18n\Time;
use \Config\Services;

class Main extends BaseController
{
    public function index()
    {
        $dailyEntryModel = new AttendanceDailyEntryModel();
        $todayEntries = $dailyEntryModel->getTodayEntries();
        $totalEntries = $dailyEntryModel->countTotalEntries();
        $attModel = new AttendanceModel();
        $todayAttendance = $attModel -> getTodayAttendanceList();
        $studentAttendance = [];
        foreach ($todayAttendance as $entry) {
            $studentAttendance[$entry['profile_id']] = $entry;
        }
        $total = 0;
        $late = 0;
        $absent = 0;

        $listStudentHome = [];
        $count = 0;
        foreach ($todayEntries as $entry) {
            if (isset($studentAttendance[$entry['profile_id']]) && $studentAttendance[$entry['profile_id']]['attendance_type_id'] == 1) {
                // Kalau ada di studentAttendance DAN attendance_type_id == 1 → status jadi 'absent'
                $entry['status'] = 'absent';
                $total++;
                $absent++;
            }elseif (isset($studentAttendance[$entry['profile_id']]) && $studentAttendance[$entry['profile_id']]['attendance_type_id'] == 4) {
                // Kalau ada di studentAttendance DAN attendance_type_id == 4 → status jadi 'late'
                $entry['status'] = 'late';
                $late++;
                $total++;
            }else{
                $entry['status'] = 'present';
                $total++;
            }

            if (in_array($entry['status'], ['present', 'late']) && $count < 4) {
                $listStudentHome[] = $entry;
                $count++;
            }
        }

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
            'late' => $late,
            'absent' => $absent,
            'present' => $totalEntries,
            'total' => $total,
        ]);
    }

    public function tapping()
    {
        $profileModel = new ProfileModel();
        $attendanceModel = new AttendanceModel();
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

            $clockInTimestamp = strtotime($clockInTime);
            $gracePeriodTimestamp = $clockInTimestamp + ($gracePeriod * 60);
            $gracePeriodTime = date('H:i:s', $gracePeriodTimestamp);

            $blockingPeriodTimestamp = $clockInTimestamp + ($BLOCKING_PERIOD * 60);
            $blockingPeriodTime = date('H:i:s', $blockingPeriodTimestamp);

            
            $dailyEntryModel = new AttendanceDailyEntryModel();
            $todayEntry = $dailyEntryModel->getTodayEntry($studentProfile['student_id']);

            $attModel = new AttendanceModel();

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
            if ($todayEntry) {
                $status = 're-tap';
                $sendEmail = false;
                if ($currentTap > $blockingPeriodTime) {
                    $status = 'home';
                    $sendEmail = true;
                    // tapping pulang
                    $dailyEntryModel->update($todayEntry['id'], [
                        'updated_by_id' => session()->get('user')['id'],
                        'clock_out' => $currentTap,
                    ]);
                }
                if ($attendanceStatus && $attendanceStatus != 'late'){
                    $sendEmail = false;
                    $status = $attendanceStatus;
                }

                $studentTappingTime = $todayEntry['clock_in'];
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
                        'profile_id'     => $studentProfile['student_id'],
                        'clock_in'       => date('Y-m-d H:i:s'),
                        'created_by_id'  => session()->get('user')['id'],
                    ]);
                }
            }

            if ($sendEmail) {
                send_email([
                    'name' => $studentProfile['first_name'].' '.$studentProfile['last_name'],
                    'kelas' => $studentData['grade_name'].' '.$studentData['code'],
                    'parent_email' => 'fauzi.ahmd72@gmail.com',
                    'time' => date('H:i:s',strtotime($studentTappingTime)),
                    'status' => $status
                ]);
            }

            return $this->response->setJSON([
                'data' => [
                    'id' => $studentData['id'],
                    'name' => $studentProfile['first_name'].' '.$studentProfile['last_name'],
                    'kelas' => $studentData['grade_name'].' '.$studentData['code'],
                    'img' => base_url('assets/users/' . ($studentProfile['profile_photo'] ?: 'default.jpg')),
                    'timestamp' => $studentTappingTime,
                    'time' => date('H:i:s',strtotime($studentTappingTime)),
                    'status' => $status
                ]
            ]);
        } 

        return $this->response->setStatusCode(404)->setJSON([
            'message' => 'Siswa tidak ditemukan'
        ]);
    }
}
