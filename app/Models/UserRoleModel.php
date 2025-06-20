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
        return $this
            ->join('profile', 'profile.id = user_role.profile_id')
            ->join('role', 'role.id = user_role.role_id')
            ->where('profile.user_id', $userId)
            ->findAll();
    }

    public function getByRoleId($id)
    {
        return $this
            ->select('
                user.id as user_id,
                profile.id as profile_id,
                user.first_name,
                user.last_name,
                user.username,
            ')
            ->join('profile', 'profile.id = user_role.profile_id')
            ->join('user', 'user.id = profile.user_id')
            ->where('role_id', $id)
            ->findAll();
    }

    public function getByUserIdRoleIds($id)
    {
        $res = $this
            ->select('
                role.id
            ')
            ->join('profile', 'profile.id = user_role.profile_id')
            ->join('role', 'role.id = user_role.role_id')
            ->where('profile.user_id', $id)
            ->findAll();

        return array_column($res, 'id');
    }
}
