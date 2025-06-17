<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassSemesterModel extends Model
{
    protected $table            = 'class_semester';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'name',
        'clock_in',
        'clock_out',
        'blocking_period',
        'active',
        'grade_id',
        'semester_id',
        'created_by_id',
        'created_at',
        'updated_by_id',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Join ke tabel grade dan semester
     *
     * @return \CodeIgniter\Database\BaseBuilder
     */
    public function withGradeAndSemester()
    {
        return $this->select('class_semester.*, grade.name AS grade_name, semester.name AS semester_name')
                    ->join('grade', 'grade.id = class_semester.grade_id', 'left')
                    ->join('semester', 'semester.id = class_semester.semester_id', 'left');
    }
    public function getClassSemesterBySemesterId($id){
        return $this->select('class_semester.id, class_semester.name, class_semester.grade_id, grade.name as grade_name, grade.section_id, section.name as section_name')
                    ->join('grade', 'grade.id = class_semester.grade_id', 'left')
                    ->join('section', 'grade.section_id = section.id', 'left')
                    ->where('semester_id',$id)->findAll();
    }
}
