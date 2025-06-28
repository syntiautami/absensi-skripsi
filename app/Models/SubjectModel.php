<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table            = 'subject';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'name',
        'alt_name',
        'code',
        'active',
        'created_by_id',
        'created_at',
        'updated_by_id',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAllData(){
        return $this->orderBy('name')->findAll();
    }

    public function getById($id){
        return $this
            ->orderBy('name')
            ->where('id',$id)
            ->first();
    }
}
