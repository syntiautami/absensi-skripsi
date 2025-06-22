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
}
