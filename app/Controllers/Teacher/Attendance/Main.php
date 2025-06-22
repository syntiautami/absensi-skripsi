<?php

namespace App\Controllers\Teacher\Attendance;

use App\Controllers\BaseController;
use App\Models\AttendanceModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\StudentClassSemesterModel;
use DateTime;
use DateTimeZone;

class Main extends BaseController
{
    public function index()
    {

        $today = new DateTime();
        $dateToday = date('Y-m-d');
        $walas = session()->get('homeroom_teacher');
        if (empty($walas)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $scsModel = new StudentClassSemesterModel();
        $students = $scsModel -> getByClassSemesterId($walas['class_semester_id']);
        $scsIds = array_column($students,'id');
        
        $attModel = new AttendanceModel();
        $existing = $attModel-> getTodayAttendanceByscsId($scsIds);
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
                    'attendance_type_id' => $attendanceTypeId,
                    'date' => $dateToday,
                    'updated_by_id' => session()->get('user')['id'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                if (in_array($key, $existingIds)) {
                    // Untuk update
                    $row['id'] = $existingMap[$key];
                    $dataUpdate[] = $row;
                } else {
                    // Untuk insert
                    $row['created_by_id'] = session()->get('user')['id'];
                    $row['created_at'] = date('Y-m-d H:i:s');
                    $dataInsert[] = $row;
                }
            }
            if (!empty($dataUpdate)) {
                $attModel->updateBatch($dataUpdate, 'id');
            }

            if (!empty($dataInsert)) {
                $attModel->insertBatch($dataInsert);
            }

            if (!empty($deleteIds)) {
                $attModel->whereIn('id', $deleteIds)->delete();
            }

            return redirect()->to(base_url('teacher/attendance'))->with('success', 'Data berhasil diupdate.');
        }

        return view('teacher/attendance/index', [
            'date' => $dateToday,
            'selected_date' => $today,
            'student_class_semesters' => $students,
            'studentAttendance' => $studentAttendance,
            'viewing' => 'attendance'
        ]);
    }
}
