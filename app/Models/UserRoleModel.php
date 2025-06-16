<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRoleModel extends Model
{
    protected $table      = 'user_role';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'profile_id',
        'role_id',
        'created_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Tidak ada kolom updated_at

    public function getByUserId($userId)
    {
        return $this->select('role.name, profile.user_id')
            ->join('profile', 'profile.id = user_role.profile_id')
            ->join('role', 'role.id = user_role.role_id')
            ->where('profile.user_id', $userId)
            ->findAll();
    }
}
