<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassTimetablePeriodModel extends Model
{
    protected $table            = 'class_timetable_period';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'class_semester_subject_id',
        'timetable_period_id',
        'date',
        'active',
        'created_by_id',
        'updated_by_id',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getClassTimetableByTimetableIds($ids, $date = null){
        if (empty($date)){
            $date = date('Y-m-d');
        }
        return $this
            ->whereIn('timetable_period_id', $ids)
            ->where('date', $date)
            ->findAll();
    }

    public function getActiveClassTimetableList($ids, $date = null){
        if (empty($date)){
            $date = date('Y-m-d');
        }
        return $this
            ->select('
                class_semester_subject_id,
                timetable_period_id,
            ')
            ->whereIn('timetable_period_id', $ids)
            ->where('date', $date)
            ->where('active',1)
            ->findAll();
    }
}
