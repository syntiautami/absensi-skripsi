<?php

namespace App\Helpers;

class AttendanceHelper
{
    public const ATTENDANCE_TYPE_MAPPING = [
        '1' => 'absent',
        '2' => 'sick',
        '3' => 'excused',
        '4' => 'late',
    ];
}
