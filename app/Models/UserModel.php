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

    protected $validationRules    = [
        'email'    => 'required|valid_email|is_unique[user.email,id,{id}]',
        'username' => 'required|is_unique[user.username,id,{id}]',
        'password' => 'required|min_length[6]',
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getLoginData($data){
        return $this
            ->select('user.*,profile.profile_photo')
            ->where('username', $data)
            ->orWhere('email', $data)
            ->join('profile','profile.user_id=user.id')
            ->first();

    }
}
