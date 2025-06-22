<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherModel extends Model
{
    protected $table            = 'teacher';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'profile_id',
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
        return $this
            ->select('
                teacher.id,
                teacher.profile_id,
                profile.user_id,
                profile.profile_photo,
                user.id as user_id,
                user.first_name,
                user.last_name
            ')
            -> join('profile', 'profile.id = teacher.profile_id', 'left')
            -> join('user', 'user.id = profile.user_id', 'left')
            -> orderBy('user.first_name','user.last_name')
            -> where('active', 1)
            -> findAll();
    }

    public function getDataById($id){
        return $this
            ->select('
                teacher.id,
                teacher.profile_id,
                profile.user_id,
                user.id as user_id,
                user.first_name,
                user.last_name
            ')
            -> join('profile', 'profile.id = teacher.profile_id', 'left')
            -> join('user', 'user.id = profile.user_id', 'left')
            -> orderBy('user.first_name','user.last_name')
            -> where('active', 1)
            -> where('teacher.id', $id)
            -> first();
    }

    public function getDataByProfileId($id){
        return $this
            ->select('
                teacher.id,
                teacher.profile_id,
                profile.user_id,
                user.id as user_id,
                user.first_name,
                user.last_name
            ')
            -> join('profile', 'profile.id = teacher.profile_id', 'left')
            -> join('user', 'user.id = profile.user_id', 'left')
            -> orderBy('user.first_name','user.last_name')
            -> where('active', 1)
            -> where('teacher.profile_id', $id)
            -> first();
    }
}
