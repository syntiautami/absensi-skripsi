<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'user';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array'; // Bisa juga 'object' kalau kamu prefer
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
        'created_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Tidak ada kolom updated_at di migration ini
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getLoginData($data){
        return $this
            ->select('user.*,profile.profile_photo, profile.id as profile_id')
            ->where('username', $data)
            ->orWhere('email', $data)
            ->join('profile','profile.user_id=user.id')
            ->first();

    }
    public function getLoginDataById($id){
        return $this
            ->select('user.*,profile.profile_photo, profile.id as profile_id')
            ->where('user.id', $id)
            ->join('profile','profile.user_id=user.id')
            ->first();
    }
}
