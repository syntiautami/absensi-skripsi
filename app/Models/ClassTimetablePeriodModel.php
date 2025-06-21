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
        'active',
        'created_by_id',
        'updated_by_id',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getClassTimetableByTimetableIds($ids){
        return $this
            ->whereIn('timetable_period_id', $ids)
            ->findAll();
    }

    public function getActiveClassTimetableList($ids){
        return $this
            ->select('
                class_semester_subject_id,
                timetable_period_id,
            ')
            ->whereIn('timetable_period_id', $ids)
            ->where('active',1)
            ->findAll();
    }
}
