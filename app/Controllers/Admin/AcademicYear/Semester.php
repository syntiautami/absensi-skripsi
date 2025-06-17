<?php

namespace App\Controllers\Admin\AcademicYear;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\SemesterModel;

class Semester extends BaseController
{
    public function edit($id)
    {
        $model = new AcademicYearModel();
        $semesterModel = new SemesterModel();
        
        $academicYear = $model->find($id);
        
        if (!$academicYear) {
            return redirect()->to(base_url('admin/academic-year/'))->with('error', 'Data tidak ditemukan.');
        }
        $semesters = $semesterModel->getSemesters_from_academic_year_id($academicYear['id']);
        
        if ($this->request->getMethod() === 'POST'){
            $data = $this->request->getPost('semesters');
            foreach ($data as $semester) {
                $inSession = isset($semester['in_session']) && $semester['in_session'] ? 1 : 0;
                $semesterModel->update($semester['id'], [
                    'name'       => $semester['name'],
                    'start_date' => date('Y-m-d', strtotime($semester['start_date'])),
                    'end_date'   => date('Y-m-d', strtotime($semester['end_date'])),
                    'in_session' => $inSession,
                    'updated_by_id' => session()->get('user')['id'],
                ]);
            }
            
            return redirect()->to(base_url('admin/academic-year/'.$academicYear['id'].'/'))->with('success', 'Data berhasil diupdate.');
        }
        return view('admin/academic-year/semester/edit', [
            'academic_year' => $academicYear,
            'semesters' => $semesters,
            'viewing' => 'academic-year',
        ]);
    }
}
