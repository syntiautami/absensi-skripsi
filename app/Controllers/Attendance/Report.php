<?php

namespace App\Controllers\Attendance;

use App\Controllers\BaseController;
use App\Models\AttendanceDailyEntryModel;
use App\Models\AttendanceModel;
use App\Models\AttendanceSubjectModel;
use App\Models\ClassSemesterSubjectModel;
use App\Models\ClassTimetablePeriodModel;
use App\Models\StudentClassSemesterModel;
use App\Models\TeacherClassSemesterHomeroomModel;
use App\Models\TeacherClassSemesterSubjectModel;
use DateInterval;
use DatePeriod;
use DateTime;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Report extends BaseController
{
    function colLetterIncrement($col, $step = 1)
    {
        $colIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($col);
        $newIndex = $colIndex + $step;
        return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($newIndex);
    }

    public function attendance($id)
    {
        // class semester
        // 1. Cari semester: start_date, end_date
        $walas = [];
        
        if(session()->get('role') == 'teacher'){
            $walas = session()->get('homeroom_teacher');
            if (empty($walas)) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }else if (session()-> get('role') == 'admin'){
            $walasModel = new TeacherClassSemesterHomeroomModel();
            $walas = $walasModel -> getFromClassSemesterIdFirst($id);
        }else{
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $semester = [
            'start_date' => $walas['semester_start_date'],
            'end_date' => $walas['semester_end_date'],
        ];

        $className = $walas['grade_name'].' '.$walas['class_code'];

        $startDate = new DateTime($semester['start_date']);
        $endDate = new DateTime($semester['end_date']);
        
        // 2. Ambil siswa di class_semester_id
        $studentClassSemesterModel = new StudentClassSemesterModel();
        $students = $studentClassSemesterModel->getByClassSemesterId($id);

        $attModel = new AttendanceModel();
        $attendances = $attModel -> getAllAttendanceBycsId($id);
        // index: [student_id][date] = attendance_type_id
        $attendanceMap = [];
        foreach ($attendances as $att) {
            $studentId = $att['student_class_semester_id'];
            $date = $att['date'];

            $attendanceMap[$studentId][$date] = $att['attendance_type_id'];
        }

        $profileIds = array_column($students, 'profile_id');
        $attDailyEntryModel = new AttendanceDailyEntryModel();
        $tappingMap = [];
        if (!empty($profileIds)) {
            $tappingMap = $attDailyEntryModel -> buildTappingMap($profileIds, $semester['start_date'], $semester['end_date']);
        }

        // 3. Buat Spreadsheet
        $spreadsheet = new Spreadsheet();

        // Loop setiap bulan
        while ($startDate <= $endDate) {
            $bulan = $startDate->format('F'); // Nama bulan (ex: June)
            $tahun = $startDate->format('Y');

            // Tambahkan sheet
            $sheet = new Worksheet($spreadsheet, $bulan);
            $spreadsheet->addSheet($sheet);
            $spreadsheet->setActiveSheetIndexByName($bulan);

            // Tulis header
            $cellsToBold = ['C2', 'B3', 'B4', 'B5'];
            foreach ($cellsToBold as $cell) {
                $sheet->getStyle($cell)->getFont()->setBold(true);
            }

            $presentColor = '2E7D32';
            $holidayColor = '29434e';
            $absentColor = 'D32F2F';
            $sickColor = '17a2b8';
            $excusedColor = '1976D2';
            $lateColor =  'FFA000';

            $sheet->setCellValue('B2', 'Laporan Kehadiran Siswa');
            $sheet->getStyle('B2')->getFont()->setSize(18);
            $sheet->setCellValue('B3', 'Kelas: '.$className);
            $sheet->setCellValue('B4', 'Semester: '.$walas['semester_name'].' ' .$walas['academic_year_name']);
            $sheet->setCellValue('B5', 'Walikelas: '.$walas['first_name']. ' '.$walas['last_name']);

            // Tulis header kolom
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->setCellValue('B8', 'Nama');
            $sheet->mergeCells('B8:B9');
            $sheet->getStyle('B8:B9')->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            $col = 'C';
            $hari = [
                'Sunday'    => 'Minggu',
                'Monday'    => 'Senin',
                'Tuesday'   => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday'  => 'Kamis',
                'Friday'    => 'Jumat',
                'Saturday'  => 'Sabtu',
            ];
            // buat periode per bulan ini
            $startDateThisMonth = clone $startDate;
            $endDateThisMonth = (clone $startDate)->modify('last day of this month');

            $periods = new DatePeriod(
                $startDateThisMonth,
                new DateInterval('P1D'),
                (clone $endDateThisMonth)->modify('+1 day')
            );

            $thresholdColTotal = 'B';
            foreach ($periods as $date) {
                $currentCol = $col;
                $thresholdColTotal++;
                $thresholdColTotal++;
                $nextCol = ++$currentCol;


                $englishDay = $date->format('l');
                $dayName = $hari[$englishDay];

                
                $sheet->mergeCells($col . '8:' . $nextCol . '8');
                $sheet->setCellValue($col . '8', $dayName . ' (' . $date->format('d') . ')');
                $sheet->getStyle($col . '8:'.$nextCol.'8')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Row 9 → Jam Masuk
                $sheet->setCellValue($col . '9', 'Jam Masuk');
                $sheet->getStyle($col . '9')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getColumnDimension($col)->setAutoSize(true);

                // Row 9 → Jam Pulang
                $sheet->setCellValue($nextCol . '9', 'Jam Pulang');
                $sheet->getStyle($nextCol . '9')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getColumnDimension($nextCol)->setAutoSize(true);
                $col = ++$nextCol;
            }

            // Header kolom summary
            $summaryHeaders = [
                'sick'               => 'Sakit',
                'excused'         => 'Izin',
                'late'               => 'Terlambat',
                'absent'             => 'Alpa',
                'total_attendance'   => 'Total Kehadiran',
                'total_school_days'  => 'Total Hari Sekolah',
            ];

            $headerStyle = [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ];

            foreach ($summaryHeaders as $headers => $headerLabel) {
                $sheet->setCellValue($col . '8', $headerLabel);
                $sheet->mergeCells($col . '8:' . $col . '9');
                $sheet->getColumnDimension($col)->setAutoSize(true);
                $sheet->getStyle($col . '8:'.$col.'9')->applyFromArray($headerStyle);
                $col++;
            }

            // Tulis data siswa
            $row = 10;
            $summaryAll = [
                'sick'               => 0,
                'excused'         => 0,
                'late'               => 0,
                'absent'             => 0,
            ];
            foreach ($students as $student) {
                $studentId = $student['id'];
                $profileId = $student['profile_id'];
                $summaryData = [
                    'sick'               => 0,
                    'excused'         => 0,
                    'late'               => 0,
                    'absent'             => 0,
                    'total_attendance'   => 0,
                    'total_school_days'  => 0,
                ];
                $col = 'C';
                $sheet->setCellValue('B' . $row, $student['first_name'] . ' ' . $student['last_name']);
                $sheet->getStyle('B'.$row)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                foreach ($periods as $date){

                    $currentCol = $col;
                    $nextCol = ++$currentCol;
                    $englishDay = $date->format('l');
                    $dateStr = $date->format('Y-m-d');

                    $clockInTime = '';
                    $clockOutTime = '';
                    $attendanceType = $attendanceMap[$studentId][$dateStr] ?? null;
                    // ambil tapping data
                    $tapping = $tappingMap[$profileId][$dateStr] ?? null;
                    
                    if ($tapping) {
                        $clockInTime = $tapping['clock_in'];
                        $clockOutTime = $tapping['clock_out'];
                    }
                    $fillColor = $presentColor;
                    
                    if (in_array($englishDay, ['Saturday', 'Sunday'])) {
                        $fillColor = $holidayColor;
                        $clockInTime = '';
                    }else{
                        $summaryData['total_school_days']++;
                        if ($attendanceType == '4') {
                            $summaryData['late']++;
                            $fillColor = $lateColor;
                        } elseif ($attendanceType == '3') {
                            $summaryData['excused']++;
                            $fillColor = $excusedColor;
                        } elseif ($attendanceType == '2') {
                            $summaryData['sick']++;
                            $fillColor = $sickColor;
                        } elseif ($attendanceType == '1') {
                            $summaryData['absent']++;
                            $fillColor = $absentColor;
                        }else{
                            $summaryData['total_attendance']++;
                        }
                    }

                    // jam masuk
                    $sheet->setCellValue($col.$row, $clockInTime);
                    $sheet->getStyle($col.$row)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => [
                                'argb' => 'FFFFFFFF',
                            ],
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'argb' => $fillColor,
                            ],
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                    if ($attendanceType == '4') {
                        $fillColor = $presentColor;
                    }

                    // jam pulang
                    $sheet->setCellValue($nextCol . $row, $clockOutTime);
                    $sheet->getStyle($nextCol.$row)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => [
                                'argb' => 'FFFFFFFF',
                            ],
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'argb' => $fillColor,
                            ],
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                    $col = ++$nextCol;
                }

                // Summary Attendance
                foreach ($summaryHeaders as $key => $label) {
                    if (isset($summaryAll[$key])){
                        $summaryAll[$key] += $summaryData[$key];
                    }
                    $sheet->setCellValue($col.$row, $summaryData[$key] ?? '');
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                    $sheet->getStyle($col.$row)->applyFromArray($headerStyle);
                    $col++;
                }
                $row++;
            }

            // summary sum column
            $thresholdColTotal--;
            $thresholdColTotal--;

            $sheet->setCellValue($thresholdColTotal.$row, 'TOTAL');
            $sheet->getStyle($thresholdColTotal.$row)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ]);
            $thresholdColTotal++;
            foreach ($summaryHeaders as $key => $label) {
                if (isset($summaryAll[$key])) {
                    $sheet->setCellValue($thresholdColTotal.$row, $summaryAll[$key] ?? '');
                    $sheet->getStyle($thresholdColTotal.$row)->applyFromArray($headerStyle);
                    $thresholdColTotal++;
                }
            }
            // sheet berikutnya (bulan berikutnya)
            $startDate->modify('+1 month');
        }

        // Remove default sheet (sheet pertama kosong)
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Worksheet')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);

        // Export ke file Excel
        $filename = 'Laporan Kehadiran Siswa Kelas '.$className.' Semester '.$walas['semester_name'].' '.$walas['academic_year_name'].'.xlsx';

        // header download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

    public function attendance_subject($id)
    {
        // class semester subject
        $cssModel = new ClassSemesterSubjectModel();
        $class_semester_subject = $cssModel -> getById($id);

        $semesterStartDate = $class_semester_subject['start_date'];
        $semesterEndDate = $class_semester_subject['end_date'];
        $semester = [
            'start_date' => $semesterStartDate,
            'end_date' => $semesterEndDate,
        ];

        $className = $class_semester_subject['grade_name'].' '.$class_semester_subject['class_code'];
        $semesterName = $class_semester_subject['semester_name'].' '.$class_semester_subject['academic_year_name'];
        $subjectName = $class_semester_subject['subject_name'];
        $startDate = new DateTime($semester['start_date']);
        $endDate = new DateTime($semester['end_date']);

        $csId = $class_semester_subject['cs_id'];
        $cssId = $class_semester_subject['css_id'];

        $walasModel = new TeacherClassSemesterHomeroomModel();
        $walas = $walasModel -> getFromClassSemesterIdFirst($csId);

        $guruModel = new TeacherClassSemesterSubjectModel();
        $guruSubjects = $guruModel-> getExistingCssId($cssId);

        $teachersName = [];
        foreach ($guruSubjects as $guru) {
            $teachersName[] = $guru['first_name'].' '.$guru['last_name'];
        }

        $teacherName = implode(', ', $teachersName);
        $walasName = $walas['first_name'].' '.$walas['last_name'];

        $ctpModel = new ClassTimetablePeriodModel();
        $ctpList = $ctpModel-> getActiveByCssId($cssId);

        $ctpDays = [];
        $ctpPeriods = [];
        foreach ($ctpList as $ctp) {
            $day = $ctp['day'];

            if (!in_array($day, $ctpDays)) {
                $ctpDays[] = $day;
            }

            if (!isset($ctpPeriods[$day])){
                $ctpPeriods[$day] = [];
            }
            $ctpPeriods[$day][] = $ctp;
        }
        $ctpDays = array_column($ctpList, 'day');
        
        // 2. Ambil siswa di class_semester_id
        $studentClassSemesterModel = new StudentClassSemesterModel();
        $students = $studentClassSemesterModel->getByClassSemesterId($csId);

        $attSubjectModel = new AttendanceSubjectModel();
        $attendance_subjects = $attSubjectModel -> getByCssId($cssId);
        // index: [student_id][date] = attendance_type_id
        $attendanceSubjectMap = [];
        foreach ($attendance_subjects as $att) {
            $studentId = $att['student_class_semester_id'];
            $date = $att['date'];
            $ctpId = $att['class_timetable_period_id'];

            if (!isset($attendanceSubjectMap[$studentId])){
                $attendanceSubjectMap[$studentId] = [];
            }

            if (!isset($attendanceSubjectMap[$studentId][$ctpId])){
                $attendanceSubjectMap[$studentId][$ctpId] = [];
            }
            $attendanceSubjectMap[$studentId][$ctpId][$date] = $att['attendance_type_id'];
        }

        // 3. Buat Spreadsheet
        $spreadsheet = new Spreadsheet();

        // Loop setiap bulan
        while ($startDate <= $endDate) {
            $bulan = $startDate->format('F'); // Nama bulan (ex: June)
            $tahun = $startDate->format('Y');

            // Tambahkan sheet
            $sheet = new Worksheet($spreadsheet, $bulan);
            $spreadsheet->addSheet($sheet);
            $spreadsheet->setActiveSheetIndexByName($bulan);

            // Tulis header
            $cellsToBold = ['C2', 'B3', 'B4', 'B5','B6'];
            foreach ($cellsToBold as $cell) {
                $sheet->getStyle($cell)->getFont()->setBold(true);
            }

            $presentColor = '2E7D32';
            $holidayColor = '29434e';
            $absentColor = 'D32F2F';
            $sickColor = '17a2b8';
            $excusedColor = '1976D2';
            $lateColor =  'FFA000';

            $sheet->setCellValue('B2', 'Laporan Kehadiran Mata Pelajaran '.$subjectName);
            $sheet->getStyle('B2')->getFont()->setSize(18);
            $sheet->setCellValue('B3', 'Kelas: '.$className);
            $sheet->setCellValue('B4', 'Semester: '.$semesterName);
            $sheet->setCellValue('B5', 'Walikelas: '.$walasName);
            $sheet->setCellValue('B6', 'Guru Mata Pelajaran: '.$teacherName);

            // Tulis header kolom
            $sheet->getColumnDimension('B')->setWidth(50);
            $sheet->setCellValue('B8', 'Nama');
            $sheet->mergeCells('B8:B9');
            $sheet->getStyle('B8:B9')->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            $col = 'C';
            $hari = [
                'Sunday'    => 'Minggu',
                'Monday'    => 'Senin',
                'Tuesday'   => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday'  => 'Kamis',
                'Friday'    => 'Jumat',
                'Saturday'  => 'Sabtu',
            ];
            // buat periode per bulan ini
            $startDateThisMonth = clone $startDate;
            $endDateThisMonth = (clone $startDate)->modify('last day of this month');

            $periods = new DatePeriod(
                $startDateThisMonth,
                new DateInterval('P1D'),
                (clone $endDateThisMonth)->modify('+1 day')
            );

            $thresholdColTotal = 'B';
            foreach ($periods as $date) {
                $currentCol = $col;
                $englishDay = $date->format('l');
                $dayName = $hari[$englishDay];

                $dayOfWeek = $date->format('N');
                if (!in_array($dayOfWeek, $ctpDays)) {
                    // bukan harinya
                    continue;
                }
                if (!isset($ctpPeriods[$dayOfWeek])){
                    // gaada ctp
                    continue;
                }

                $maxLengthCol = count($ctpPeriods[$dayOfWeek]) - 1;

                $nextCol = $col;
                if ($maxLengthCol) {
                    $nextCol = $this->colLetterIncrement($col, $maxLengthCol); // increment 1 kolom → D
                }
                $thresholdColTotal = $this->colLetterIncrement($thresholdColTotal, $maxLengthCol + 1);

                $sheet->mergeCells($col . '8:' . $nextCol . '8');
                $sheet->setCellValue($col . '8', $dayName . ' (' . $date->format('d') . ')');
                $sheet->getStyle($col . '8:'.$nextCol.'8')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                $subCol = $col;
                foreach ($ctpPeriods[$dayOfWeek] as $period) {
                    $sheet->setCellValue($subCol . '9', 'Period '.$period['period']);
                    $sheet->getStyle($subCol . '9')->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                    $subCol ++;
                }

                // $sheet->getColumnDimension($nextCol)->setAutoSize(true);
                $col = ++$nextCol;
            }

            // Header kolom summary
            $summaryHeaders = [
                'sick'               => 'Sakit',
                'excused'         => 'Izin',
                'late'               => 'Terlambat',
                'absent'             => 'Alpa',
            ];

            $headerStyle = [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ];

            foreach ($summaryHeaders as $headers => $headerLabel) {
                $sheet->setCellValue($col . '8', $headerLabel);
                $sheet->mergeCells($col . '8:' . $col . '9');
                $sheet->getColumnDimension($col)->setAutoSize(true);
                $sheet->getStyle($col . '8:'.$col.'9')->applyFromArray($headerStyle);
                $col++;
            }

            // Tulis data siswa
            $row = 10;
            $summaryAll = [
                'sick'               => 0,
                'excused'         => 0,
                'late'               => 0,
                'absent'             => 0,
            ];
            foreach ($students as $student) {
                $studentId = $student['id'];
                $summaryData = [
                    'sick'               => 0,
                    'excused'         => 0,
                    'late'               => 0,
                    'absent'             => 0,
                    'total_attendance'   => 0,
                    'total_school_days'  => 0,
                ];
                $col = 'C';
                $sheet->setCellValue('B' . $row, $student['first_name'] . ' ' . $student['last_name']);
                $sheet->getStyle('B'.$row)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $studentAttendanceMap = $attendanceSubjectMap[$studentId] ?? [];

                foreach ($periods as $date){

                    $currentCol = $col;
                    $englishDay = $date->format('l');
                    $dateStr = $date->format('Y-m-d');

                    $dayOfWeek = $date->format('N');
                    if (!in_array($dayOfWeek, $ctpDays)) {
                        // bukan harinya
                        continue;
                    }
                    if (!isset($ctpPeriods[$dayOfWeek])){
                        // gaada ctp
                        continue;
                    }

                    $subCol = $col;
                    foreach ($ctpPeriods[$dayOfWeek] as $period) {
                        $attendanceType = $studentAttendanceMap[$period['ctp_id']][$dateStr] ?? null;
                        $fillColor = $presentColor;
                        
                        if (in_array($englishDay, ['Saturday', 'Sunday'])) {
                            $fillColor = $holidayColor;
                        }else{
                            $summaryData['total_school_days']++;
                            if ($attendanceType == '4') {
                                $summaryData['late']++;
                                $fillColor = $lateColor;
                            } elseif ($attendanceType == '3') {
                                $summaryData['excused']++;
                                $fillColor = $excusedColor;
                            } elseif ($attendanceType == '2') {
                                $summaryData['sick']++;
                                $fillColor = $sickColor;
                            } elseif ($attendanceType == '1') {
                                $summaryData['absent']++;
                                $fillColor = $absentColor;
                            }else{
                                $summaryData['total_attendance']++;
                            }
                        }
    
                        $sheet->setCellValue($subCol.$row, '');
                        $sheet->getStyle($subCol.$row)->applyFromArray([
                            'font' => [
                                'bold' => true,
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'argb' => $fillColor,
                                ],
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        $subCol++;
                    }
                    $col = $subCol;
                }

                // Summary Attendance
                foreach ($summaryHeaders as $key => $label) {
                    if (isset($summaryAll[$key])){
                        $summaryAll[$key] += $summaryData[$key];
                    }
                    $sheet->setCellValue($col.$row, $summaryData[$key] ?? '');
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                    $sheet->getStyle($col.$row)->applyFromArray($headerStyle);
                    $col++;
                }
                $row++;
            }

            // summary sum column
            $sheet->setCellValue($thresholdColTotal.$row, 'TOTAL');
            $sheet->getStyle($thresholdColTotal.$row)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ]);
            $thresholdColTotal++;
            foreach ($summaryHeaders as $key => $label) {
                if (isset($summaryAll[$key])) {
                    $sheet->setCellValue($thresholdColTotal.$row, $summaryAll[$key] ?? '');
                    $sheet->getStyle($thresholdColTotal.$row)->applyFromArray($headerStyle);
                    $thresholdColTotal++;
                }
            }
            // sheet berikutnya (bulan berikutnya)
            $startDate->modify('+1 month');
        }

        // Remove default sheet (sheet pertama kosong)
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Worksheet')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);

        // Export ke file Excel
        $filename = 'Laporan Kehadiran Mata Pelajaran '.$subjectName.' Kelas '.$className.' Semester '.$semesterName.'.xlsx';

        // header download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
