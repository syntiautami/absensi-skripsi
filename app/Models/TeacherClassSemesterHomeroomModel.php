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

    
    public function getByProfileId($id){
        return $this
            ->select('
                teacher_class_semester_homeroom.id,
                teacher_class_semester_homeroom.class_semester_id,
                teacher_class_semester_homeroom.teacher_id,
                teacher.profile_id,
                section.name as section_name,
                grade.name as grade_name,
                class_semester.name as class_code,
                semester.name as semester_name,
                semester.start_date as semester_start_date,
                semester.end_date as semester_end_date,
                academic_year.name as academic_year_name,
                profile.profile_photo,
                user.first_name,
                user.last_name
            ')
            ->join('teacher','teacher.id = teacher_class_semester_homeroom.teacher_id')
            ->join('profile','profile.id = teacher.profile_id')
            ->join('user','user.id = profile.user_id')
            ->join('class_semester','class_semester.id = teacher_class_semester_homeroom.class_semester_id')
            ->join('grade','grade.id = class_semester.grade_id')
            ->join('semester','semester.id = class_semester.semester_id')
            ->join('academic_year','academic_year.id = semester.academic_year_id')
            ->join('section','section.id = grade.section_id')
            ->where([
                'teacher.profile_id' => $id,
                'semester.in_session' =>1,
            ])
            ->first();
    }
    public function getFromClassSemesterId($id)
    {
        return $this->select('
                teacher_class_semester_homeroom.id,
                teacher_class_semester_homeroom.class_semester_id,
                user.first_name,
                user.last_name,
                profile.profile_photo,
                teacher_class_semester_homeroom.teacher_id,
            ')
            ->join('class_semester', 'class_semester.id = teacher_class_semester_homeroom.class_semester_id', 'left')
            ->join('teacher', 'teacher.id = teacher_class_semester_homeroom.teacher_id', 'left')
            ->join('profile', 'profile.id = teacher.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left')
            ->where('class_semester_id',$id)
            ->findAll();
    }
    public function getFromClassSemesterIdFirst($id)
    {
        return $this->select('
                teacher_class_semester_homeroom.id,
                teacher_class_semester_homeroom.class_semester_id,
                teacher_class_semester_homeroom.teacher_id,
                teacher.profile_id,
                section.name as section_name,
                grade.name as grade_name,
                class_semester.name as class_code,
                semester.name as semester_name,
                semester.start_date as semester_start_date,
                semester.end_date as semester_end_date,
                academic_year.name as academic_year_name,
                profile.profile_photo,
                user.first_name,
                user.last_name
            ')
            ->join('teacher','teacher.id = teacher_class_semester_homeroom.teacher_id')
            ->join('profile','profile.id = teacher.profile_id')
            ->join('user','user.id = profile.user_id')
            ->join('class_semester','class_semester.id = teacher_class_semester_homeroom.class_semester_id')
            ->join('grade','grade.id = class_semester.grade_id')
            ->join('semester','semester.id = class_semester.semester_id')
            ->join('academic_year','academic_year.id = semester.academic_year_id')
            ->join('section','section.id = grade.section_id')
            ->where('class_semester_id',$id)
            ->first();
    }
    public function getFromClassSemesterIds($id)
    {
        return $this->select('
                teacher_class_semester_homeroom.id,
                teacher_class_semester_homeroom.class_semester_id,
                user.first_name,
                user.last_name,
                profile.profile_photo,
            ')
            ->join('class_semester', 'class_semester.id = teacher_class_semester_homeroom.class_semester_id', 'left')
            ->join('teacher', 'teacher.id = teacher_class_semester_homeroom.teacher_id', 'left')
            ->join('profile', 'profile.id = teacher.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left')
            ->whereIn('class_semester_id',$id)
            ->findAll();
    }
}
