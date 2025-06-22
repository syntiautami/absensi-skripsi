<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassSemesterSubjectModel extends Model
{
    protected $table            = 'class_semester_subject';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'class_semester_id',
        'subject_id',
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
     * Join ke class_semester dan subject sekaligus
     *
     * @return \CodeIgniter\Database\BaseBuilder
     */
    public function getAllSubjectByClassSemesterId($id){
        return $this
            ->select('
                class_semester_subject.id,
                subject.name
            ')
            ->join('subject', 'subject.id = class_semester_subject.subject_id')
            ->where('class_semester_id',$id)
            ->where('class_semester_subject.active', 1)
            ->orderBy('subject.name')
            ->findAll();
    }

    public function getExistingSubjectsByCsyId($csId){
        return $this
            ->select('
                subject_id,
                class_semester_id
            ')
            ->join('class_semester', 'class_semester.id = class_semester_subject.class_semester_id', 'left')
            ->where('class_semester.class_semester_year_id', $csId)
            ->findAll();
    }

    public function getActiveExistingSubjectByCsyId($csyId){
        return $this
            ->select('subject_id')
            ->join('class_semester', 'class_semester.id = class_semester_subject.class_semester_id', 'left')
            ->where('class_semester.class_semester_year_id', $csyId)
            ->where('class_semester.active', 1)
            ->where('class_semester_subject.active', 1)
            ->findAll();
    }

    public function getActiveSubjectsByCsyId($csyId){
        return $this
            ->select('
                subject_id,
                subject.name as subject_name,
                class_semester_subject.id as css_id,
                class_semester.class_semester_year_id,
                class_semester.id as class_semester_id
            ')
            ->join('class_semester', 'class_semester.id = class_semester_subject.class_semester_id', 'left')
            ->join('subject','subject.id = class_semester_subject.subject_id')
            ->where('class_semester.class_semester_year_id', $csyId)
            ->where('class_semester.active', 1)
            ->where('class_semester_subject.active', 1)
            ->findAll();
    }
}
