<?php

namespace App\Controllers\Admin\Subject;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\ClassSemesterModel;
use App\Models\ClassSemesterSubjectModel;
use App\Models\ClassSemesterYearModel;
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

        $csyModel = new ClassSemesterYearModel();
        $class_semester_years = $csyModel-> getClassSemesterYearByAcademicYearId($id);

        return view('admin/subject/classes/classes', [
            'academic_year' => $academicYear,
            'class_semester_years' => $class_semester_years,
            'viewing' => 'class-subject',
        ]);
    }

    public function class_subjects($academicYearId, $id){
        $academicYearModel = new AcademicYearModel();
        $academicYear = $academicYearModel ->getAcademicYearById($academicYearId);
        if (!$academicYear) {
            return redirect()->to(base_url('admin/subject/class/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $csyModel = new ClassSemesterYearModel();
        $class_semester_year = $csyModel-> getById($id);
        if (!$class_semester_year) {
            return redirect()->to(base_url('admin/subject/class/academic-year/'.$academicYearId.'/'))->with('error', 'Data tidak ditemukan.');
        }

        $subjectModel = new SubjectModel();
        $cssModel = new ClassSemesterSubjectModel();
        
        if ($this->request->getMethod() == 'POST'){
            $data = $this->request->getPost('subjects');
            
            $existingSubjects = $cssModel-> getExistingSubjectsByCsyId($id);
            $existingClassSemesterSubjectMap = [];
            foreach ($existingSubjects as $row) {
                if (!isset($existingClassSemesterSubjectMap[$row['class_semester_id']])) {
                    $existingClassSemesterSubjectMap[$row['class_semester_id']] = [];
                }
                $existingClassSemesterSubjectMap[$row['class_semester_id']][] = $row['subject_id'];
            }

            $insertBatch = [];
            $updateBatch = [];
            $userId = session()->get('user')['id'];

            $csModel = new ClassSemesterModel();
            $class_semesters = $csModel->getCsByCsyId($id);
            if (!empty($data)) {
                foreach ($class_semesters as $class_semester) {
                    $class_semester_id = $class_semester['id'];
                    $existingSubjects = $existingClassSemesterSubjectMap[$class_semester_id] ?? [];
                    foreach ($data as $subjectId) {
                        if (!in_array($subjectId, $existingSubjects)) {
                            // Belum ada → insert
                            $insertBatch[] = [
                                'class_semester_id' => $class_semester_id,
                                'subject_id'        => $subjectId,
                                'active'            => 1,
                                'created_by_id'     => $userId,
                            ];
                        } else {
                            // Sudah ada → update
                            $updateBatch[] = [
                                'class_semester_id' => $class_semester_id,
                                'subject_id'        => $subjectId,
                                'active'            => 1,
                                'updated_by_id'     => $userId,
                            ];
                        }
                    }
                }
    
                // 5. Insert batch
                if (!empty($insertBatch)) {
                    $cssModel->insertBatch($insertBatch);
                }
                if (!empty($updateBatch)) {
                    foreach ($updateBatch as $updateRow) {
                        $cssModel
                            ->where('class_semester_id', $updateRow['class_semester_id'])
                            ->where('subject_id', $updateRow['subject_id'])
                            ->set([
                                'active' => 1,
                                'updated_by_id' => $updateRow['updated_by_id'],
                            ])
                            ->update();
                    }
                }
            }else{
                foreach ($class_semesters as $class_semester){
                    $cssModel
                        ->where('class_semester_id',$class_semester['id'])
                        ->set([
                            'active' => 0,
                            'updated_by_id' => $userId
                        ])
                        ->update();
                }
            }

            return redirect()->to(base_url('admin/subject/class/academic-year/'.$academicYearId.'/class_semester_year/'.$id.'/'))->with('success', 'Data berhasil diubah.');
        }

        $existingActiveSubjects = $cssModel-> getActiveExistingSubjectByCsyId($id);
        $existingMap = [];
        foreach ($existingActiveSubjects as $row) {
            $existingMap[] = $row['subject_id'];
        }

        $subjects = $subjectModel-> getAllData();

        return view('admin/subject/classes/class_subjects', [
            'academic_year' => $academicYear,
            'class_semester_year' => $class_semester_year,
            'existing_subjects' => $existingMap,
            'subjects' => $subjects,
            'viewing' => 'class-subject',
        ]);
    }
}
