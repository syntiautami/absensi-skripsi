<?php

namespace App\Models;

use CodeIgniter\Model;

class TimetablePeriodModel extends Model
{
    protected $table            = 'timetable_period';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'day_of_week',
        'period',
        'start_time',
        'end_time',
        'created_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';

    public function getTimetable($period){
        return $this
            ->where ('day_of_week', $period)
            ->orWhere('day_of_week', null)
            ->findAll();
    }
}
