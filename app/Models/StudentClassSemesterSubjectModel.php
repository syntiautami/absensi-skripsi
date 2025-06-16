<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentClassSemesterSubjectModel extends Model
{
    protected $table            = 'student_class_semester_subject';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'student_class_semester_id',
        'class_semester_subject_id',
        'active',
        'created_by_id',
        'created_at',
        'updated_by_id',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Join ke student_class_semester (untuk ambil info siswa & kelas)
     */
    public function withStudentClassSemester()
    {
        return $this->select('
                student_class_semester_subject.*,
                profile.nisn,
                user.first_name,
                user.last_name,
                class_semester.name AS class_name
            ')
            ->join('student_class_semester', 'student_class_semester.id = student_class_semester_subject.student_class_semester_id', 'left')
            ->join('profile', 'profile.id = student_class_semester.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left')
            ->join('class_semester', 'class_semester.id = student_class_semester.class_semester_id', 'left');
    }

    /**
     * Join ke class_semester_subject (untuk info mapel)
     */
    public function withClassSemesterSubject()
    {
        return $this->select('
                student_class_semester_subject.*,
                subject.name AS subject_name
            ')
            ->join('class_semester_subject', 'class_semester_subject.id = student_class_semester_subject.class_semester_subject_id', 'left')
            ->join('subject', 'subject.id = class_semester_subject.subject_id', 'left');
    }

    /**
     * Join lengkap: siswa, kelas, dan mapel
     */
    public function withAll()
    {
        return $this->select('
                student_class_semester_subject.*,
                profile.nisn,
                user.first_name,
                user.last_name,
                class_semester.name AS class_name,
                subject.name AS subject_name
            ')
            ->join('student_class_semester', 'student_class_semester.id = student_class_semester_subject.student_class_semester_id', 'left')
            ->join('profile', 'profile.id = student_class_semester.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left')
            ->join('class_semester', 'class_semester.id = student_class_semester.class_semester_id', 'left')
            ->join('class_semester_subject', 'class_semester_subject.id = student_class_semester_subject.class_semester_subject_id', 'left')
            ->join('subject', 'subject.id = class_semester_subject.subject_id', 'left');
    }
}
