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

    /**
     * Join dengan data user guru (via profile)
     */
    public function withProfile()
    {
        return $this->select('
                teacher.*,
                user.first_name,
                user.last_name
            ')
            ->join('profile', 'profile.id = teacher.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left');
    }

    /**
     * Join dengan user pembuat dan pengubah
     */
    public function withUserAudit()
    {
        return $this->select('
                teacher.*,
                creator.username AS created_by_username,
                updater.username AS updated_by_username
            ')
            ->join('user AS creator', 'creator.id = teacher.created_by_id', 'left')
            ->join('user AS updater', 'updater.id = teacher.updated_by_id', 'left');
    }

    /**
     * Join lengkap: user guru + user audit
     */
    public function withAll()
    {
        return $this->select('
                teacher.*,
                user.first_name,
                user.last_name,
                creator.username AS created_by_username,
                updater.username AS updated_by_username
            ')
            ->join('profile', 'profile.id = teacher.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left')
            ->join('user AS creator', 'creator.id = teacher.created_by_id', 'left')
            ->join('user AS updater', 'updater.id = teacher.updated_by_id', 'left');
    }
}
