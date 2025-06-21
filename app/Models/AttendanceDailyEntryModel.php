<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;

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

    public function buildTappingMap(array $profileIds, $startDate, $endDate): array
    {
        $result = $this
            ->select('profile_id, clock_in, clock_out, created_at')
            ->whereIn('profile_id', $profileIds)
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate)
            ->findAll();

        $tappingMap = [];

        foreach ($result as $entry) {
            if (empty($entry['profile_id'])) {
                dd($entry);
                continue;
            }
            $profileId = $entry['profile_id'];
            $dateStr = (new DateTime($entry['created_at']))->format('Y-m-d');

            $tappingMap[$profileId][$dateStr] = [
                'clock_in'  => $entry['clock_in'] ? (new DateTime($entry['clock_in']))->format('H:i:s') : '',
                'clock_out' => $entry['clock_out'] ? (new DateTime($entry['clock_out']))->format('H:i:s') : '',
            ];
        }

        return $tappingMap;
    }


    public function getTodayEntry($id)
    {
        return $this
            ->select('*')
            ->where('profile_id', $id)
            ->where('DATE(created_at)', date('Y-m-d'))
            ->orderBy('clock_in', 'asc')
            ->first();
    }

    public function cronHelper($ids, $date)
    {
        return $this
            ->select('profile_id')
            ->whereIn('profile_id', $ids)
            ->where('clock_in >=', $date)
            ->findAll();
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
