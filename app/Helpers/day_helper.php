<?php

if (!function_exists('period_day')) {
    function day_indonesian($period)
    {
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        return isset($days[$period]) ? $days[$period] : '-';
    }
}
