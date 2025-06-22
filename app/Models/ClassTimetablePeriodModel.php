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
        'day',
        'active',
        'created_by_id',
        'updated_by_id',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getClassTimetableByTimetableIds($ids, $day){
        return $this
            ->whereIn('timetable_period_id', $ids)
            ->where('day',$day)
            ->findAll();
    }

    public function getActiveClassTimetableList($ids, $day){
        return $this
            ->select('
                class_semester_subject_id,
                timetable_period_id,
            ')
            ->whereIn('timetable_period_id', $ids)
            ->where('day',$day)
            ->where('active',1)
            ->findAll();
    }

    public function getActiveByCssIds($ids){
        return $this
            ->select('
                class_semester_subject_id as css_id,
                day,
                timetable_period_id,
                timetable_period.start_time,
                timetable_period.end_time,
            ')
            ->join('timetable_period','timetable_period.id = class_timetable_period.timetable_period_id')
            ->whereIn('class_semester_subject_id', $ids)
            ->where('active',1)
            ->findAll();
    }
}
