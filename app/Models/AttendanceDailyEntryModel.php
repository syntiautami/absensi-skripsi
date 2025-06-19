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

    public function getTodayEntries()
    {
        return $this
            ->select('
                attendance_daily_entry.clock_in,
                attendance_daily_entry.clock_out,
                student.id as student_id,
                student.profile_id,
                profile.profile_photo,
                user.first_name,
                user.last_name,
            ')
            ->join('student','student.profile_id = attendance_daily_entry.profile_id')
            ->join('profile','profile.id = attendance_daily_entry.profile_id')
            ->join('user','user.id = profile.user_id')
            ->where('DATE(clock_in)', date('Y-m-d'))
            ->orWhere('DATE(clock_out)', date('Y-m-d'))
            ->orderBy('clock_in', 'desc')
            ->findAll();
    }
    public function countTotalEntries($date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d');
        }

        return $this
            ->where('DATE(clock_in)', $date)
            ->orWhere('DATE(clock_out)', $date)
            ->countAllResults();
    }
}
