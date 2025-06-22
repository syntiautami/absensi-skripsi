<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassSemesterYearModel extends Model
{
   protected $table            = 'class_semester_year';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'code',
        'academic_year_id',
        'grade_id',
        'created_by_id',
        'updated_by_id',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

}
