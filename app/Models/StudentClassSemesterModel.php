<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentClassSemesterModel extends Model
{
    protected $table            = 'student_class_semester';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'student_id',
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
     * Join dengan data profile + user (siswa)
     */
    public function getByClassSemesterId($id)
    {
        return $this
            ->select('
                student_class_semester.id,
                student_class_semester.student_id,
                student.profile_id,
                profile.barcode_number,
                user.first_name,
                user.last_name
            ')
            ->join('student', 'student.id = student_class_semester.student_id', 'left')
            ->join('profile', 'profile.id = student.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left')
            ->where('student_class_semester.active',1)
            ->where('class_semester_id',$id)
            ->orderBy('first_name, last_name')
            ->findAll();
    }

    /**
     * Join dengan data class_semester
     */
    public function withClassSemester()
    {
        return $this->select('
                student_class_semester.*,
                class_semester.name AS class_name,
                class_semester.semester_id
            ')
            ->join('class_semester', 'class_semester.id = student_class_semester.class_semester_id', 'left');
    }

    /**
     * Ambil data lengkap dengan profile (user) & class_semester
     */
    public function withAll()
    {
        return $this->select('
                student_class_semester.*,
                profile.nisn,
                user.first_name,
                user.last_name,
                class_semester.name AS class_name,
                class_semester.semester_id
            ')
            ->join('student', 'student.id = student_class_semester.student_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left')
            ->join('class_semester', 'class_semester.id = student_class_semester.class_semester_id', 'left');
    }

    public function getCountByClassSemesterId($id){
        return $this->where('class_semester_id',$id)->countAllResults();
    }
}
