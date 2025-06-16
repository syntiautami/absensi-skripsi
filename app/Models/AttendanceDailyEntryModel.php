<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceDailyEntryModel extends Model
{
    protected $table            = 'attendance_daily_entry';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'profile_id',
        'clock_in',
        'clock_out',
        'created_by_id',
        'created_at',
        'updated_by_id',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Join ke profile + user (untuk ambil nama)
     */
    public function withProfile()
    {
        return $this->select('
                attendance_daily_entry.*,
                user.first_name AS profile_first_name,
                user.last_name AS profile_last_name,
                profile.nisn
            ')
            ->join('profile', 'profile.id = attendance_daily_entry.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left');
    }

    /**
     * Join ke user pembuat & pengubah
     */
    public function withUser()
    {
        return $this->select('
                attendance_daily_entry.*,
                created_by.username AS created_by_username,
                updated_by.username AS updated_by_username
            ')
            ->join('user AS created_by', 'created_by.id = attendance_daily_entry.created_by_id', 'left')
            ->join('user AS updated_by', 'updated_by.id = attendance_daily_entry.updated_by_id', 'left');
    }

    /**
     * Join ke semua relasi: profile -> user, dan pembuat/pengubah
     */
    public function withAll()
    {
        return $this->select('
                attendance_daily_entry.*,
                profile.nisn,
                user.first_name AS profile_first_name,
                user.last_name AS profile_last_name,
                created_by.username AS created_by_username,
                updated_by.username AS updated_by_username
            ')
            ->join('profile', 'profile.id = attendance_daily_entry.profile_id', 'left')
            ->join('user', 'user.id = profile.user_id', 'left')
            ->join('user AS created_by', 'created_by.id = attendance_daily_entry.created_by_id', 'left')
            ->join('user AS updated_by', 'updated_by.id = attendance_daily_entry.updated_by_id', 'left');
    }
}
