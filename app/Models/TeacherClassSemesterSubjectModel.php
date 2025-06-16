<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherClassSemesterSubjectModel extends Model
{
    protected $table            = 'teacher_class_semester_subject';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'teacher_id',
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
     * Join ke tabel teacher dan user
     */
    public function withTeacher()
    {
        return $this->select('
                teacher_class_semester_subject.*,
                user.first_name AS teacher_first_name,
                user.last_name AS teacher_last_name
            ')
            ->join('teacher', 'teacher.id = teacher_class_semester_subject.teacher_id', 'left')
            ->join('profile', 'profile.id = teacher.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left');
    }

    /**
     * Join ke class_semester_subject dan subject
     */
    public function withSubject()
    {
        return $this->select('
                teacher_class_semester_subject.*,
                subject.name AS subject_name,
                class_semester.name AS class_name
            ')
            ->join('class_semester_subject', 'class_semester_subject.id = teacher_class_semester_subject.class_semester_subject_id', 'left')
            ->join('subject', 'subject.id = class_semester_subject.subject_id', 'left')
            ->join('class_semester', 'class_semester.id = class_semester_subject.class_semester_id', 'left');
    }

    /**
     * Join lengkap: guru, mapel, kelas
     */
    public function withAll()
    {
        return $this->select('
                teacher_class_semester_subject.*,
                user.first_name AS teacher_first_name,
                user.last_name AS teacher_last_name,
                subject.name AS subject_name,
                class_semester.name AS class_name
            ')
            ->join('teacher', 'teacher.id = teacher_class_semester_subject.teacher_id', 'left')
            ->join('profile', 'profile.id = teacher.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left')
            ->join('class_semester_subject', 'class_semester_subject.id = teacher_class_semester_subject.class_semester_subject_id', 'left')
            ->join('subject', 'subject.id = class_semester_subject.subject_id', 'left')
            ->join('class_semester', 'class_semester.id = class_semester_subject.class_semester_id', 'left');
    }
}
