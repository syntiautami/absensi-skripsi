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

    public function getTodayEntry($id)
    {
        return $this
            ->select('*')
            ->where('profile_id', $id)
            ->where('DATE(clock_in)', date('Y-m-d'))
            ->orWhere('DATE(clock_out)', date('Y-m-d'))
            ->orderBy('clock_in', 'asc')
            ->first();
    }
}
