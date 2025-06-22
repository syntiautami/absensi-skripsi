<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table            = 'student';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array'; // Bisa diganti jadi 'object' jika kamu prefer pakai objek
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'profile_id',
        'active',
        'nis',
        'nisn',
        'created_by_id',
        'created_at',
        'updated_by_id',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAllData(){
        return $this
            ->select(
                'student.id, 
                student.profile_id, 
                profile.user_id,
                profile.profile_photo, 
                profile.barcode_number, 
                user.first_name, 
                user.last_name'
            )
            ->join('profile','profile.id = student.profile_id')
            ->join('user','user.id = profile.user_id')
            ->findAll();
    }

    public function getByUserId($id){
        return $this
            ->select(
                'student.*'
            )
            ->join('profile','profile.id = student.profile_id')
            ->where('profile.user_id',$id)
            ->first();
    }

    public function excludeStudentsIds($ids){
        return $this
            ->select(
                'student.id, 
                student.profile_id, 
                profile.user_id,
                profile.barcode_number,
                profile.profile_photo,  
                user.first_name, 
                user.last_name'
            )
            ->join('profile','profile.id = student.profile_id')
            ->join('user','user.id = profile.user_id')
            ->whereNotIn('student.id', $ids)
            ->findAll();
    }
}
