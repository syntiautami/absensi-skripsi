<?php

namespace App\Controllers\Admin\AcademicYear;

use App\Controllers\BaseController;

use App\Models\AcademicYearModel;
use App\Models\SemesterModel;

class Main extends BaseController
{
    public function index()
    {
        $model = new AcademicYearModel();
        $academicYears = $model->orderBy('start_date','DESC')->findAll();
        return view('admin/academic-year/index', [
            'academic_years' => $academicYears,
            'viewing' => 'academic-year',
        ]);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required',
                'start_date'    => 'required|valid_date',
                'end_date'      => 'required|valid_date',
            ];

            if ($this->validate($rules)) {
                $data = $this->request->getPost();
                $active_semester_id = (int)$data['active_semester_id'];
                $in_session = $this->request->getPost('in_session') ? 1 : 0;
                $model = new AcademicYearModel();
                if ($in_session) {
                    $model -> where('in_session', 1)
                        -> set([
                            'in_session' => 0,
                            'updated_by_id' => session()->get('user')['id']
                        ])
                        -> update();
                }
                $model->insert([
                    'name'          => $data['name'],
                    'start_date'    => date('Y-m-d H:i:s', strtotime($data['start_date'])),
                    'end_date'      => date('Y-m-d H:i:s', strtotime($data['end_date'])),
                    'in_session'    => $in_session,
                ]);

                $semesterModel = new SemesterModel();
                $semesterModel-> insertBatch([
                    [
                        'name' => $data['first_semester-name'],
                        'start_date'    => date('Y-m-d H:i:s', strtotime($data['first_semester-start_date'])),
                        'end_date'      => date('Y-m-d H:i:s', strtotime($data['first_semester-end_date'])),
                        'in_session'    => $active_semester_id == 1 ? 1 : 0,
                    ],
                    [
                        'name' => $data['second_semester-name'],
                        'start_date'    => date('Y-m-d H:i:s', strtotime($data['second_semester-start_date'])),
                        'end_date'      => date('Y-m-d H:i:s', strtotime($data['second_semester-end_date'])),
                        'in_session'    => $active_semester_id == 2 ? 1 : 0,
                    ],
                ]);

                return redirect()->to('admin/academic-year/')->with('success', 'Data tahun akademik berhasil ditambahkan.');
            }
        }

        return view('admin/academic-year/create');
    }

    public function detail($id)
    {
        $academicYearModel = new AcademicYearModel();
        $academicYear = $academicYearModel ->where('id',$id)->first();
        return view('admin/academic-year/detail', [
            'academic_year' => $academicYear,
            'viewing' => 'academic-year',
        ]);
    }

    public function edit($id)
    {
        $model = new AcademicYearModel();
        $semesterModel = new SemesterModel();
        
        $academicYear = $model->find($id);
        
        if (!$academicYear) {
            return redirect()->to(base_url('admin/academic-year/'))->with('error', 'Data tidak ditemukan.');
        }
        
        if ($this->request->getMethod() === 'POST'){
            $data = $this->request->getPost();
            $in_session = $this->request->getPost('in_session') ? 1 : 0;
            if ($in_session) {
                $model -> where('in_session', 1) 
                        -> set([
                            'in_session' => 0,
                            'updated_by_id' => session()->get('user')['id']
                        ])
                        -> update();
            }
            $semester_id_insession = (int)$data['active_semester_id'];
            $current_semester_in_session = $semesterModel 
                -> where([
                    'academic_year_id' => $id,
                    'in_session' => 1
                ])->first();

            if ($current_semester_in_session['id'] != $semester_id_insession){
                $semesterModel -> update($current_semester_in_session['id'], [
                    'in_session' => 0,
                    'updated_by_id' => session()->get('user')['id']
                ]);

                $semesterModel -> update($semester_id_insession, [
                    'in_session' => 1,
                    'updated_by_id' => session()->get('user')['id']
                ]);
            }
            
            $model->update($id, [
                'name'          => $data['name'],
                'start_date' => date('Y-m-d H:i:s', strtotime($data['start_date'])),
                'end_date' => date('Y-m-d H:i:s', strtotime($data['end_date'])),
                'in_session'    => $in_session,
                'updated_by_id' => session()->get('user')['id'],
            ]);
            return redirect()->to(base_url('admin/academic-year/'))->with('success', 'Data berhasil diupdate.');
        }
        $semesters = $semesterModel->where('academic_year_id',$id)->findAll();
        return view('admin/academic-year/edit', [
            'academic_year' => $academicYear,
            'semesters' => $semesters,
            'viewing' => 'academic-year',
        ]);
    }
}
