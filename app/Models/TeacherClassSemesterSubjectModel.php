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

    public function getExistingSubjectByCsyId($csyId){
        return $this
            ->select('
                teacher_class_semester_subject.id as tcss_id,
                class_semester_subject.id as css_id,
                class_semester_subject.subject_id,
                teacher_id,
            ')
            ->join('class_semester_subject','class_semester_subject.id = teacher_class_semester_subject.class_semester_subject_id', 'left')
            ->join('class_semester','class_semester.id = class_semester_subject.class_semester_id', 'left')
            ->where('class_semester.class_semester_year_id', $csyId)
            ->where('teacher_class_semester_subject.active',1)
            ->where('class_semester_subject.active',1)
            ->where('class_semester.active',1)
            ->findAll();
    }

    public function getInSessionTcssByTeacher($teacherId){
        return $this
            ->select('
                teacher_class_semester_subject.id as tcss_id,
                class_semester_subject.id as css_id,
                class_semester_subject.subject_id,
                subject.name as subject_name,
                academic_year.name as academic_year_name,
                semester.name as semester_name,
                semester.id as semester_id,
                grade.name as grade_name,
                class_semester_year.code as class_code,
                teacher_id,
            ')
            ->join('class_semester_subject','class_semester_subject.id = teacher_class_semester_subject.class_semester_subject_id', 'left')
            ->join('subject','subject.id = class_semester_subject.subject_id', 'left')
            ->join('class_semester','class_semester.id = class_semester_subject.class_semester_id', 'left')
            ->join('class_semester_year','class_semester_year.id = class_semester.class_semester_year_id', 'left')
            ->join('grade','grade.id = class_semester_year.grade_id', 'left')
            ->join('semester','semester.id = class_semester.semester_id','left')
            ->join('academic_year','academic_year.id = semester.academic_year_id','left')
            ->where('teacher_id', $teacherId)
            ->where('teacher_class_semester_subject.active',1)
            ->where('class_semester_subject.active',1)
            ->where('class_semester.active',1)
            ->where('semester.in_session',1)
            ->orderBy('subject.name')
            ->findAll();
    }

}
