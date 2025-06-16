<?php

namespace App\Models;

use CodeIgniter\Model;

class AcademicYearModel extends Model
{
    protected $table      = 'academic_year';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'name',
        'in_session',
        'active',
        'start_date',
        'end_date',
        'created_by_id',
        'updated_by_id',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
