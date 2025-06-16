<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceSubjectModel extends Model
{
    protected $table            = 'attendance_subject';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'attendance_type_id',
        'student_class_semester_subject_id',
        'date',
        'created_by_id',
        'created_at',
        'updated_by_id',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function withAttendanceType()
    {
        return $this->select('
                attendance_subject.*,
                attendance_type.name AS type_name
            ')
            ->join('attendance_type', 'attendance_type.id = attendance_subject.attendance_type_id', 'left');
    }

    public function withStudentProfile()
    {
        return $this->select('
                attendance_subject.*,
                profile.nisn AS student_nisn,
                user.first_name AS student_first_name,
                user.last_name AS student_last_name,
                subject.name AS subject_name
            ')
            ->join('student_class_semester_subject', 'student_class_semester_subject.id = attendance_subject.student_class_semester_subject_id', 'left')
            ->join('student_class_semester', 'student_class_semester.id = student_class_semester_subject.student_class_semester_id', 'left')
            ->join('student', 'student.id = student_class_semester.student_id', 'left')
            ->join('profile', 'profile.id = student.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left')
            ->join('subject', 'subject.id = student_class_semester_subject.subject_id', 'left');
    }

    public function withUser()
    {
        return $this->select('
                attendance_subject.*,
                created_by.username AS created_by_username,
                updated_by.username AS updated_by_username
            ')
            ->join('user AS created_by', 'created_by.id = attendance_subject.created_by_id', 'left')
            ->join('user AS updated_by', 'updated_by.id = attendance_subject.updated_by_id', 'left');
    }

    public function withAll()
    {
        return $this->select('
                attendance_subject.*,
                attendance_type.name AS type_name,
                profile.nisn AS student_nisn,
                user.first_name AS student_first_name,
                user.last_name AS student_last_name,
                subject.name AS subject_name,
                created_by.username AS created_by_username,
                updated_by.username AS updated_by_username
            ')
            ->join('attendance_type', 'attendance_type.id = attendance_subject.attendance_type_id', 'left')
            ->join('student_class_semester_subject', 'student_class_semester_subject.id = attendance_subject.student_class_semester_subject_id', 'left')
            ->join('student_class_semester', 'student_class_semester.id = student_class_semester_subject.student_class_semester_id', 'left')
            ->join('student', 'student.id = student_class_semester.student_id', 'left')
            ->join('profile', 'profile.id = student.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left')
            ->join('subject', 'subject.id = student_class_semester_subject.subject_id', 'left')
            ->join('user AS created_by', 'created_by.id = attendance_subject.created_by_id', 'left')
            ->join('user AS updated_by', 'updated_by.id = attendance_subject.updated_by_id', 'left');
    }
}
