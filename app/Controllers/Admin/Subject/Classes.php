<?php

namespace App\Controllers\Admin\Subject;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\ClassSemesterModel;
use App\Models\ClassSemesterSubjectModel;
use App\Models\SubjectModel;
use CodeIgniter\HTTP\ResponseInterface;

class Classes extends BaseController
{
    public function index()
    {
        $model = new AcademicYearModel();
        $academicYears = $model->orderedAcademicYear();
        return view('admin/subject/classes/index', [
            'academic_years' => $academicYears,
            'viewing' => 'class-subject',
        ]);
    }

    public function classes($id){
        $academicYearModel = new AcademicYearModel();
        $academicYear = $academicYearModel ->getAcademicYearById($id);
        if (!$academicYear) {
            return redirect()->to(base_url('admin/subject/class/'))->with('error', 'Data tidak ditemukan.');
        }

        $csModel = new ClassSemesterModel();
        $classSemesters = $csModel->getPivotClassSemesterByAcademicYear($id);

        return view('admin/subject/classes/classes', [
            'academic_year' => $academicYear,
            'classSemesters' => $classSemesters['tableData'],
            'semesters' => $classSemesters['semesterList'],
            'viewing' => 'report',
        ]);
    }

    public function class_subjects($academicYearId, $id){
        $academicYearModel = new AcademicYearModel();
        $academicYear = $academicYearModel ->getAcademicYearById($academicYearId);
        if (!$academicYear) {
            return redirect()->to(base_url('admin/subject/class/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $csModel = new ClassSemesterModel();
        $class_semester = $csModel-> getClassSemesterById($id);
        
        if (!$class_semester) {
            return redirect()->to(base_url('admin/subject/class/academic-year/'.$academicYearId.'/'))->with('error', 'Data tidak ditemukan.');
        }
        $subjectModel = new SubjectModel();
        $cssModel = new ClassSemesterSubjectModel();

        
        if ($this->request->getMethod() == 'POST'){
            $data = $this->request->getPost('subjects');
            
            $existingSubjects = $cssModel-> getExistingSubjectsById($id);
            $existingMap = [];
            foreach ($existingSubjects as $row) {
                $existingMap[$row['subject_id']] = $row['active'];
            }

            $insertBatch = [];
            $userId = session()->get('user')['id'];

            if (!empty($data)) {
                foreach ($data as $subjectId) {
                    if (!isset($existingMap[$subjectId])) {
                        // Belum ada → insert
                        $insertBatch[] = [
                            'class_semester_id' => $id,
                            'subject_id'        => $subjectId,
                            'active'            => 1,
                            'created_by_id'     => $userId,
                        ];
                    } elseif ($existingMap[$subjectId] == 0) {
                        // Ada, tapi belum active → update
                        $cssModel
                            ->where('class_semester_id',$id)
                            ->where('subject_id',$subjectId)
                            ->set([
                                'active' => 1,
                                'updated_by_id' => $userId
                            ])
                            ->update();
                    }
                }
    
                // 5. Insert batch
                if (!empty($insertBatch)) {
                    $cssModel->insertBatch($insertBatch);
                }
            }else{
                $cssModel
                    ->where('class_semester_id',$id)
                    ->set([
                        'active' => 0,
                        'updated_by_id' => $userId
                    ])
                    ->update();
            }

            return redirect()->to(base_url('admin/subject/class/academic-year/'.$academicYearId.'/class/'.$id.'/'))->with('success', 'Data berhasil diubah.');
        }

        $existingActiveSubjects = $cssModel-> getActiveExistingSubjectByCsId($id);
        $existingMap = [];
        foreach ($existingActiveSubjects as $row) {
            $existingMap[] = $row['subject_id'];
        }

        $subjects = $subjectModel-> getAllData();

        return view('admin/subject/classes/class_subjects', [
            'academic_year' => $academicYear,
            'class_semester' => $class_semester,
            'existing_subjects' => $existingMap,
            'subjects' => $subjects,
            'viewing' => 'report',
        ]);
    }
}
