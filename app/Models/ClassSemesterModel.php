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
        'clock_in',
        'clock_out',
        'blocking_period',
        'grace_period',
        'active',
        'class_semester_year_id',
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
                class_semester_year.code as class_code,
                grade.id as grade_id,
                grade.name AS grade_name,
                semester.id AS semester_id,
                semester.name AS semester_name
            ')
            ->join('class_semester_year', 'class_semester_year.id = class_semester.class_semester_year_id')
            ->join('grade', 'grade.id = class_semester_year.grade_id', 'left')
            ->join('semester', 'semester.id = class_semester.semester_id', 'left')
            ->where('semester.academic_year_id', $id)
            ->findAll();
    }

    public function getBySemesterIdAndCsyId($semester_id, $csy_id){
        return $this
            ->where('semester_id',$semester_id)
            ->where('class_semester_year_id',$csy_id)
            ->first();
    }

    public function getCsByCsyId($id){
        return $this
            ->select('
                class_semester.id,
                class_semester.grace_period,
                class_semester.clock_in,
                class_semester.clock_out,
                class_semester_year.code as class_code,
                class_semester_year.grade_id,
                grade.name as grade_name,
                grade.section_id,
                semester.name as semester_name
            ')
            ->join('class_semester_year', 'class_semester_year.id = class_semester.class_semester_year_id')
            ->join('grade', 'grade.id = class_semester_year.grade_id', 'left')
            ->join('semester', 'semester.id = class_semester.semester_id', 'left')
            ->where('class_semester_year_id',$id)
            ->orderBy('semester_name')
            ->findAll();
    }

    public function getByCsyId($ids){
        return $this
            ->select('
                class_semester.id,
                class_semester.class_semester_year_id,
                class_semester_year.code as class_code,
                class_semester_year.grade_id,
                grade.name as grade_name,
                grade.section_id,
                semester.id as semester_id,
                semester.name as semester_name,
                (
                    SELECT COUNT(*) FROM student_class_semester 
                    JOIN student on student.id = student_class_semester.student_id
                    JOIN profile on profile.id = student.profile_id
                    WHERE 
                        student_class_semester.student_id is not null and
                        student.profile_id is not null and
                        profile.user_id is not null and
                        student_class_semester.class_semester_id = class_semester.id
                ) AS total_students
            ')
            ->join('class_semester_year', 'class_semester_year.id = class_semester.class_semester_year_id')
            ->join('grade', 'grade.id = class_semester_year.grade_id', 'left')
            ->join('semester', 'semester.id = class_semester.semester_id', 'left')
            ->whereIn('class_semester_year_id',$ids)
            ->findAll();
    }

    public function getClassSemesterByClassSemesterYearId($id){
        return $this
            ->select('
                class_semester.id,
                class_semester_year.code as class_code,
                class_semester_year.grade_id,
                grade.name as grade_name,
                grade.section_id,
                (
                    SELECT COUNT(*) FROM student_class_semester 
                    JOIN student on student.id = student_class_semester.student_id
                    JOIN profile on profile.id = student.profile_id
                    WHERE 
                        student_class_semester.student_id is not null and
                        student.profile_id is not null and
                        profile.user_id is not null and
                        student_class_semester.class_semester_id = class_semester.id
                ) AS total_students
            ')
            ->join('class_semester_year', 'class_semester_year.id = class_semester.class_semester_year_id')
            ->join('grade', 'grade.id = class_semester_year.grade_id', 'left')
            ->where('class_semester_year_id',$id)
            ->findAll();
    }

    public function getClassSemesterBySemesterId($id){
        return $this
            ->select('
                class_semester.id,
                class_semester_year.code as class_code,
                class_semester_year.grade_id,
                grade.name as grade_name,
                grade.section_id,
                (
                    SELECT COUNT(*) FROM student_class_semester 
                    JOIN student on student.id = student_class_semester.student_id
                    JOIN profile on profile.id = student.profile_id
                    WHERE 
                        student_class_semester.student_id is not null and
                        student.profile_id is not null and
                        profile.user_id is not null and
                        student_class_semester.class_semester_id = class_semester.id
                ) AS total_students
            ')
            ->join('class_semester_year', 'class_semester_year.id = class_semester.class_semester_year_id')
            ->join('grade', 'grade.id = class_semester_year.grade_id', 'left')
            ->where('semester_id',$id)->findAll();
    }
    
    public function getClassSemesterById($id){
        return $this
            ->select('
                class_semester.id,
                class_semester_year.code as class_code,
                class_semester.grace_period,
                class_semester.clock_in,
                class_semester.clock_out,
                class_semester_year.grade_id,
                grade.name as grade_name,
                grade.section_id,
            ')
            ->join('class_semester_year', 'class_semester_year.id = class_semester.class_semester_year_id')
            ->join('grade', 'grade.id = class_semester_year.grade_id', 'left')
            ->where('class_semester.id',$id)->first();
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
        ksort($tableData);
        return [
            'tableData' => $tableData,
            'semesterList' => $semesterList,
        ];
    }

    public function getCronFunc($date, $todayDate){
        return $this
            ->select('
                class_semester.clock_in,
                class_semester.clock_out,
                class_semester.id as cs_id,
                class_semester_year.code as class_code,
                class_semester.grace_period,
                grade.name as grade_name,
            ')
            ->join('semester', 'semester.id = class_semester.semester_id', 'left')
            ->join('academic_year', 'academic_year.id = semester.academic_year_id', 'left')
            ->join('class_semester_year', 'class_semester_year.id = class_semester.class_semester_year_id')
            ->join('grade', 'grade.id = class_semester_year.grade_id', 'left')
            ->where('academic_year.in_session',1)
            ->where('academic_year.active',1)
            ->where('grade.active', 1)
            ->where('semester.active',1)
            ->where('semester.in_session',1)
            ->where('DATE(semester.start_date) <=', $date)
            ->where('DATE(semester.end_date) >=', $todayDate)
            ->where('class_semester.clock_in is not null')
            ->findAll();
    }
}