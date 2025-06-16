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
    public function withClassSemesterAndSubject()
    {
        return $this->select('
                    class_semester_subject.*,
                    class_semester.name AS class_name,
                    subject.name AS subject_name
                ')
                ->join('class_semester', 'class_semester.id = class_semester_subject.class_semester_id', 'left')
                ->join('subject', 'subject.id = class_semester_subject.subject_id', 'left');
    }
}
