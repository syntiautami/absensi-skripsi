<?php

namespace App\Models;

use CodeIgniter\Model;

class GradeModel extends Model
{
    protected $table            = 'grade';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'name',
        'active',
        'order',
        'section_id',
        'created_by_id',
        'created_at',
        'updated_by_id',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Join ke tabel section untuk ambil data jenjang/tingkatan
     *
     * @return \CodeIgniter\Database\BaseBuilder
     */
    public function getAllData(){
        return $this
            -> select('
                grade.id,
                grade.name
            ')
            ->orderBy('order','name')
            ->findAll();
    }

    public function withSection()
    {
        return $this
            ->select('
                grade.id,
                grade.name,
                grade.section_id,
                section.name AS section_name
            ')
            ->join('section', 'section.id = grade.section_id', 'left')
            ->orderBy('section.name asc, grade.order asc')
            ->findAll();
    }
}
