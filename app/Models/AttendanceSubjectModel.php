<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceSubjectModel extends Model
{
    protected $table            = 'attendance_subject';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'attendance_type_id',
        'student_class_semester_id',
        'class_timetable_period_id',
        'date',
        'created_by_id',
        'created_at',
        'updated_by_id',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAttendanceSubjectExisting($ids, $ctpId, $date){
        return $this
            ->where('date',$date)
            ->where('class_timetable_period_id',$ctpId)
            ->whereIn('student_class_semester_id', $ids)
            ->findAll();
    }

    public function getByCssId($id){
        return $this
            ->join('class_timetable_period', 'class_timetable_period.id = attendance_subject.class_timetable_period_id','left')
            ->join('class_semester_subject','class_semester_subject.id = class_timetable_period.class_semester_subject_id', 'left')
            ->where('class_semester_subject.id',$id)
            ->where('class_timetable_period.active',1)
            ->where('class_semester_subject.active',1)
            ->findAll();
    }
}
