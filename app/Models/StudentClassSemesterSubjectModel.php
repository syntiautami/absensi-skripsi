<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentClassSemesterSubjectModel extends Model
{
    protected $table            = 'student_class_semester_subject';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'student_class_semester_id',
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


}
