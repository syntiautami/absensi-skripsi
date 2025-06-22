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
                class_timetable_period.id as ctp_id,
                class_semester_subject_id as css_id,
                day,
                timetable_period.start_time,
                timetable_period.end_time,
            ')
            ->join('timetable_period','timetable_period.id = class_timetable_period.timetable_period_id')
            ->whereIn('class_semester_subject_id', $ids)
            ->where('active',1)
            ->findAll();
    }

    public function getById($id){
        return $this
            ->select('
                subject.name as subject_name,
                grade.name as grade_name,
                class_semester_year.code as class_code,
                class_timetable_period.id as ctp_id,
                class_semester_subject_id as css_id,
                class_semester_subject.class_semester_id as cs_id,
            ')
            ->join('timetable_period','timetable_period.id = class_timetable_period.timetable_period_id')
            ->join('class_semester_subject','class_semester_subject.id = class_timetable_period.class_semester_subject_id')
            ->join('subject','subject.id = class_semester_subject.subject_id')
            ->join('class_semester','class_semester.id = class_semester_subject.class_semester_id')
            ->join('class_semester_year','class_semester_year.id = class_semester.class_semester_year_id')
            ->join('grade','grade.id = class_semester_year.grade_id')
            ->where('class_timetable_period.id', $id)
            ->where('class_timetable_period.active',1)
            ->where('class_semester_subject.active',1)
            ->where('class_semester.active',1)
            ->first();
    }
}
