<?php

namespace App\Controllers\Teacher\Report;

use App\Controllers\BaseController;
use App\Models\AttendanceDailyEntryModel;
use App\Models\AttendanceModel;
use App\Models\SemesterModel;
use App\Models\StudentClassSemesterModel;
use CodeIgniter\HTTP\ResponseInterface;
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

class Main extends BaseController
{
    public function index()
    {
        $dateToday = date('Y-m-d');
        $walas = session()->get('homeroom_teacher');
        if (empty($walas)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }


        return view('teacher/report/index', [
            'viewing' => 'report',
            '$walas' => $walas,
        ]);
    }

    public function exportData($id)
    {
        // 1. Cari semester: start_date, end_date
        $walas = session()->get('homeroom_teacher');
        if (empty($walas)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $semester = [
            'start_date' => $walas['semester_start_date'],
            'end_date' => $walas['semester_end_date'],
        ];

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
        $tappingMap = $attDailyEntryModel -> buildTappingMap($profileIds, $semester['start_date'], $semester['end_date']);

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
            $sheet->setCellValue('B3', 'Kelas: '.$walas['section_name'].' '.$walas['grade_name'].' '.$walas['class_code']);
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
            foreach ($periods as $date) {
                $currentCol = $col;
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
                'permission'         => 'Izin',
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
            foreach ($students as $student) {
                $studentId = $student['id'];
                $summaryData = [
                    'sick'               => 0,
                    'permission'         => 0,
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
                    $tapping = $tappingMap[$studentId][$dateStr] ?? null;
                    
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
                            $fillColor = $lateColor;
                        } elseif ($attendanceType == '3') {
                            $fillColor = $excusedColor;
                        } elseif ($attendanceType == '2') {
                            $fillColor = $sickColor;
                        } elseif ($attendanceType == '1') {
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
                    $sheet->setCellValue($col.$row, $summaryData[$key] ?? '');
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                    $sheet->getStyle($col.$row)->applyFromArray($headerStyle);
                    $col++;
                }
                $row++;
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
        $filename = 'Laporan_Kehadiran_Siswa_' . date('Ymd') . '.xlsx';

        // header download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
