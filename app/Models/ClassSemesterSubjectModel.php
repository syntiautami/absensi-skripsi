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
                subject.id,
                subject.name
            ')
            ->join('subject', 'subject.id = class_semester_subject.subject_id')
            ->where('class_semester_id',$id)
            ->where('class_semester_subject.active', 1)
            ->orderBy('subject.name')
            ->findAll();
    }

    public function getExistingSubjectsById($csId){
        return $this
            ->select('
                subject_id,
                active
            ')
            ->where('class_semester_id', $csId)
            ->findAll();
    }

    public function getActiveExistingSubjectByCsId($csId){
        return $this
            ->select('subject_id')
            ->where('class_semester_id', $csId)
            ->where('active', 1)
            ->findAll();
    }
}
