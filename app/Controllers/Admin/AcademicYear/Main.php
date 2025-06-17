<?php

namespace App\Controllers\Admin\AcademicYear;

use App\Controllers\BaseController;

use App\Models\AcademicYearModel;

class Main extends BaseController
{
    public function index()
    {
        $model = new AcademicYearModel();
        $academicYears = $model ->findAll();
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
                $in_session = $this->request->getPost('in_session') ? 1 : 0;
                $data = [
                    'name' => $this->request->getPost('name'),
                    'start_date' => date('Y-m-d H:i:s', strtotime($this->request->getPost('start_date'))),
                    'end_date' => date('Y-m-d H:i:s', strtotime($this->request->getPost('end_date'))),
                    'in_session'     => $in_session,
                ];
                $model = new AcademicYearModel();
                if ($in_session) {
                    $model -> where('in_session', 1) -> set(['in_session' => 0]) -> update();
                }
                $model->insert($data);

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
        $model = new \App\Models\AcademicYearModel();
        $academicYear = $model->find($id);

        if (!$academicYear) {
            return redirect()->to(base_url('admin/academic-year/'))->with('error', 'Data tidak ditemukan.');
        }

        if ($this->request->getMethod() === 'POST'){
            $data = $this->request->getPost();
            $in_session = $this->request->getPost('in_session') ? 1 : 0;
            if ($in_session) {
                $model -> where('in_session', 1) -> set(['in_session' => 0]) -> update();
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

        return view('admin/academic-year/edit', [
            'academic_year' => $academicYear,
            'viewing' => 'academic-year',
        ]);
    }
}
