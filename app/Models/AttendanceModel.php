<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table            = 'attendance';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'attendance_type_id',
        'student_class_semester_id',
        'date',
        'created_by_id',
        'created_at',
        'updated_by_id',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getTodayAttendanceByscsId($ids){
        return $this
        ->whereIn('student_class_semester_id', $ids)
        ->where('date', date('Y-m-d'))
        ->findAll();
    }
    public function getTodayAttendance($id){
        $this->where('student_class_semester_id', $id)
        ->where('DATE(date)', date('Y-m-d'))
        ->first();
    }
    
    public function getTodayAttendanceList($date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d');
        }

        return $this
            ->select('
                attendance_type_id,
                date,
                student.profile_id
            ')
            ->join('student_class_semester', 'student_class_semester.id = attendance.student_class_semester_id')
            ->join('student', 'student_class_semester.student_id = student.id')
            ->where('date', $date)
            ->findAll();
    }

    public function countSummaryByDate($date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d');
        }

        return $this
            ->select('
                SUM(attendance_type_id = 1) AS total_absent,
                SUM(attendance_type_id = 4) AS total_late
            ')
            ->where('date', $date)
            ->first();
    }
    /**
     * Join dengan attendance_type
     */
    public function withAttendanceType()
    {
        return $this->select('
                attendance.*,
                attendance_type.name AS type_name
            ')
            ->join('attendance_type', 'attendance_type.id = attendance.attendance_type_id', 'left');
    }

    /**
     * Join dengan data student → profile → user
     */
    public function withStudent()
    {
        return $this->select('
                attendance.*,
                profile.nisn AS student_nisn,
                user.first_name AS student_first_name,
                user.last_name AS student_last_name
            ')
            ->join('student_class_semester', 'student_class_semester.id = attendance.student_class_semester_id', 'left')
            ->join('student', 'student.id = student_class_semester.student_id', 'left')
            ->join('profile', 'profile.id = student.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left');
    }

    /**
     * Join semua: attendance_type, student → profile → user
     */
    public function withAll()
    {
        return $this->select('
                attendance.*,
                attendance_type.name AS type_name,
                profile.nisn AS student_nisn,
                user.first_name AS student_first_name,
                user.last_name AS student_last_name
            ')
            ->join('attendance_type', 'attendance_type.id = attendance.attendance_type_id', 'left')
            ->join('student_class_semester', 'student_class_semester.id = attendance.student_class_semester_id', 'left')
            ->join('student', 'student.id = student_class_semester.student_id', 'left')
            ->join('profile', 'profile.id = student.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left');
    }
}
