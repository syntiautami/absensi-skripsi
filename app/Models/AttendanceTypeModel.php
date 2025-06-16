<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceTypeModel extends Model
{
    protected $table            = 'attendance_type';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = ['name'];

    public $timestamps = false;
}
