<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassSemesterModel extends Model
{
    protected $table            = 'class_semester';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'name',
        'clock_in',
        'clock_out',
        'blocking_period',
        'grace_period',
        'active',
        'grade_id',
        'semester_id',
        'created_by_id',
        'created_at',
        'updated_by_id',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Join ke tabel grade dan semester
     *
     * @return \CodeIgniter\Database\BaseBuilder
     */
    public function getByAcademicYearId($id)
    {
        return $this
            ->select('
                class_semester.id as cs_id,
                class_semester.name as class_code,
                grade.id as grade_id,
                grade.name AS grade_name,
                semester.id AS semester_id,
                semester.name AS semester_name
            ')
            ->join('grade', 'grade.id = class_semester.grade_id', 'left')
            ->join('semester', 'semester.id = class_semester.semester_id', 'left')
            ->where('semester.academic_year_id', $id)
            ->findAll();
    }

    public function getPivotClassSemesterByAcademicYear($id){
        $res = $this->getByAcademicYearId($id);
        $tableData = [];
        $semesterList = [];

        foreach ($res as $row) {
            $kelasKey = $row['grade_id'] . $row['class_code'];

            if (!isset($tableData[$kelasKey])) {
                $tableData[$kelasKey] = [
                    'kelas' => $row['grade_name'].' '.$row['class_code'],
                ];
            }
            $tableData[$kelasKey][$row['semester_name']] = $row;

            if (!in_array($row['semester_name'], $semesterList)) {
                $semesterList[] = $row['semester_name'];
            }
        }
        return [
            'tableData' => $tableData,
            'semesterList' => $semesterList,
        ];
    }
}