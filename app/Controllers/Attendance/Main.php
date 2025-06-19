<?php

namespace App\Controllers\Attendance;

use App\Controllers\BaseController;
use App\Models\Attendance;
use App\Models\AttendanceDailyEntryModel;
use App\Models\AttendanceModel;
use App\Models\ProfileModel;
use App\Models\StudentClassSemesterModel;
use \CodeIgniter\I18n\Time;

class Main extends BaseController
{
    public function index()
    {
        return view('attendance/index', [
            'date' => Time::now('Asia/Jakarta', 'en_ID')->getTimestamp()
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
                
            
            if ($todayEntry) {
                $status = 're-tap';
                if ($currentTap > $blockingPeriodTime) {
                    $status = 'home';
                }
                // tapping pulang
                $dailyEntryModel->update($todayEntry['id'], [
                    'updated_by_id' => session()->get('user')['id'],
                ]);
            } else {
                // tapping masuk
                $status = 'absent';
                if ($currentTap < $clockInTime) {
                    $status = 'present';
                } elseif (!empty($gracePeriod) && $currentTap >= $clockInTime && $currentTap <= $gracePeriodTime) {
                    $status = 'late';
                    if (!$existingAttendance){
                        $attModel -> insert([
                            'student_class_semester_id' => $studentData['id'],
                            'attendance_type_id' => 4, //late
                            'created_by_id'  => session()->get('user')['id']
                        ]);
                    }
                } else {
                    if (!$existingAttendance) {
                        $attModel -> insert([
                            'student_class_semester_id' => $studentData['id'],
                            'attendance_type_id' => 1, //absent
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

            return $this->response->setJSON([
                'data' => [
                    'id' => $studentData['id'],
                    'name' => $studentProfile['first_name'].' '.$studentProfile['last_name'],
                    'kelas' => $studentData['section_name'].' '.$studentData['grade_name'].' '.$studentData['code'],
                    'img' => base_url('assets/users/' . ($studentProfile['profile_photo'] ?: 'default.jpg')),
                    'timestamp' => $currentTap,
                    'status' => $status
                ]
            ]);
        } 

        return $this->response->setStatusCode(404)->setJSON([
            'message' => 'Siswa tidak ditemukan'
        ]);
    }
}
