<?php

namespace App\Models;

use CodeIgniter\Model;

class SemesterModel extends Model
{
    protected $table            = 'semester';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array'; // Bisa diganti 'object' kalau perlu
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'name',
        'in_session',
        'active',
        'academic_year_id',
        'start_date',
        'end_date',
        'order',
        'created_by_id',
        'created_at',
        'updated_by_id',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Join ke tabel academic_year untuk ambil data tahun ajaran
     *
     * @return \CodeIgniter\Database\BaseBuilder
     */
    public function withAcademicYear()
    {
        return $this->select('semester.*, academic_year.name AS academic_year_name')->join('academic_year', 'academic_year.id = semester.academic_year_id', 'left');
    }

    public function getSemesters_from_academic_year_id($id){
        return $this->where('academic_year_id',$id)->orderBy('start_date','ASC')->findAll();
    }
}
