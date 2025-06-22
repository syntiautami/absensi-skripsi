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

    public function getClassSemesterYearByAcademicYearId($id){
        return $this
            ->select('
                class_semester_year.id,
                grade.name as grade_name,
                class_semester_year.code as class_code,
            ')
            ->join('grade', 'grade.id = class_semester_year.grade_id', 'left')
            ->where('academic_year_id', $id)
            ->orderBy('grade_name','code')
            ->findAll();
    }

    public function getById($id){
        return $this
            ->select('
                class_semester_year.id,
                grade.name as grade_name,
                class_semester_year.code as class_code,
            ')
            ->join('grade', 'grade.id = class_semester_year.grade_id', 'left')
            ->where('class_semester_year.id', $id)
            ->orderBy('code')
            ->first();
    }
}
