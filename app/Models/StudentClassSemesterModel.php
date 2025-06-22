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
    public function getByStudentIds($ids)
    {
        return $this
            ->select('
                student_class_semester.id,
                class_semester.id as cs_id,
                grade.name as grade_name,
                class_semester_year.code as class_code,
                class_semester.grace_period,
                class_semester.clock_in,
                class_semester.clock_out,
                student.profile_id
            ')
            ->join('class_semester', 'class_semester.id = student_class_semester.class_semester_id', 'left')
            ->join('class_semester_year', 'class_semester_year.id = class_semester.class_semester_year_id')
            ->join('grade', 'grade.id = class_semester_year.grade_id', 'left')
            ->join('student', 'student.id = student_class_semester.student_id', 'left')
            ->where('student_class_semester.active',1)
            ->whereIn('student_class_semester.student_id',$ids)
            ->findAll();
    }
            
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
            ->where('student_class_semester.student_id is not null')
            ->where('student.profile_id is not null')
            ->where('profile.user_id is not null')
            ->orderBy('first_name, last_name')
            ->findAll();
    }

    public function getByClassSemesterIds($ids)
    {
        return $this
            ->select('
                student_class_semester.id,
                student_class_semester.student_id,
                student_class_semester.class_semester_id,
                student.profile_id,
                profile.barcode_number,
                profile.parent_email,
                user.first_name,
                user.last_name,
                class_semester_year.code as class_code,
                grade.name as grade_name,
            ')
            ->join('student', 'student.id = student_class_semester.student_id', 'left')
            ->join('profile', 'profile.id = student.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left')
            ->join('class_semester', 'class_semester.id = student_class_semester.class_semester_id','left')
            ->join('class_semester_year', 'class_semester_year.id = class_semester.class_semester_year_id')
            ->join('grade', 'grade.id = class_semester_year.grade_id', 'left')
            ->where('student_class_semester.active',1)
            ->whereIn('class_semester_id',$ids)
            ->where('student_class_semester.student_id is not null')
            ->where('student.profile_id is not null')
            ->where('profile.user_id is not null')
            ->orderBy('first_name, last_name')
            ->findAll();
    }
    public function getById($id)
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
            ->where('student_class_semester.id',$id)
            ->orderBy('first_name, last_name')
            ->first();
    }

    public function getByStudentId($id)
    {
        return $this
            ->select('
                student_class_semester.id,
                class_semester.id as cs_id,
                grade.name as grade_name,
                class_semester_year.code as class_code,
                class_semester.grace_period,
                class_semester.clock_in,
                class_semester.clock_out,
            ')
            ->join('class_semester', 'class_semester.id = student_class_semester.class_semester_id', 'left')
            ->join('class_semester_year', 'class_semester_year.id = class_semester.class_semester_year_id')
            ->join('grade', 'grade.id = class_semester_year.grade_id', 'left')
            ->where('student_class_semester.student_id',$id)
            ->first();
    }

    public function getCountByClassSemesterId($id){
        return $this->where('class_semester_id',$id)->countAllResults();
    }


    public function getByCsyIds($ids)
    {
        return $this
            ->select('
                student_class_semester.id,
                student_class_semester.student_id,
                student_class_semester.class_semester_id,
                student.profile_id,
                profile.barcode_number,
                profile.parent_email,
                user.first_name,
                user.last_name,
                class_semester_year.code as class_code,
                grade.name as grade_name,
                class_semester.class_semester_year_id
            ')
            ->join('student', 'student.id = student_class_semester.student_id', 'left')
            ->join('profile', 'profile.id = student.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left')
            ->join('class_semester', 'class_semester.id = student_class_semester.class_semester_id','left')
            ->join('class_semester_year', 'class_semester_year.id = class_semester.class_semester_year_id')
            ->join('grade', 'grade.id = class_semester_year.grade_id', 'left')
            ->where('student_class_semester.active',1)
            ->where('class_semester.active',1)
            ->whereIn('class_semester.class_semester_year_id',$ids)
            ->where('student_class_semester.student_id is not null')
            ->where('student.profile_id is not null')
            ->where('profile.user_id is not null')
            ->orderBy('first_name, last_name')
            ->findAll();
    }
}
