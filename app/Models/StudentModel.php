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
        'created_by_id',
        'created_at',
        'updated_by_id',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Join ke tabel profile
     */
    public function withProfile()
    {
        return $this->select('
                    student.*,
                    profile.first_name,
                    profile.last_name,
                    profile.nisn
                ')
                ->join('profile', 'profile.id = student.profile_id', 'left');
    }

    /**
     * Ambil semua data student aktif + data profil
     */
    public function getActiveWithProfile()
    {
        return $this->withProfile()
                    ->where('student.active', true)
                    ->findAll();
    }
}
