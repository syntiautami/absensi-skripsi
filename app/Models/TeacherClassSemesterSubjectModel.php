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

}
