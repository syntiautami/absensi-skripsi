<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherClassSemesterHomeroomModel extends Model
{
    protected $table            = 'teacher_class_semester_homeroom';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'teacher_id',
        'class_semester_id',
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
     * Join ke guru dan user-nya
     */
    public function withTeacher()
    {
        return $this->select('
                teacher_class_semester_homeroom.*,
                user.first_name AS teacher_first_name,
                user.last_name AS teacher_last_name,
            ')
            ->join('teacher', 'teacher.id = teacher_class_semester_homeroom.teacher_id', 'left')
            ->join('profile', 'profile.id = teacher.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left');
    }

    /**
     * Join ke class_semester dan semester
     */
    public function withClassSemester()
    {
        return $this->select('
                teacher_class_semester_homeroom.*,
                class_semester.name AS class_name,
                semester.name AS semester_name
            ')
            ->join('class_semester', 'class_semester.id = teacher_class_semester_homeroom.class_semester_id', 'left')
            ->join('semester', 'semester.id = class_semester.semester_id', 'left');
    }

    /**
     * Join lengkap: guru + kelas + semester
     */
    public function withAll()
    {
        return $this->select('
                teacher_class_semester_homeroom.*,
                user.first_name AS teacher_first_name,
                user.last_name AS teacher_last_name,
                class_semester.name AS class_name,
                semester.name AS semester_name
            ')
            ->join('teacher', 'teacher.id = teacher_class_semester_homeroom.teacher_id', 'left')
            ->join('profile', 'profile.id = teacher.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left')
            ->join('class_semester', 'class_semester.id = teacher_class_semester_homeroom.class_semester_id', 'left')
            ->join('semester', 'semester.id = class_semester.semester_id', 'left');
    }
}
