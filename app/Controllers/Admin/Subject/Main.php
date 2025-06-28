<?php

namespace App\Controllers\Admin\Subject;

use App\Controllers\BaseController;
use App\Models\SubjectModel;
use CodeIgniter\HTTP\ResponseInterface;

class Main extends BaseController
{
    public function index()
    {
        $subjectModel = new SubjectModel();
        $subjects = $subjectModel->getAllData();
        return view('admin/subject/index', [
            'subjects' => $subjects,
            'viewing' => 'subject',
        ]);
    }

    public function create()
    {
        $subjectModel = new SubjectModel();

        if ($this->request->getMethod() == 'POST') {
            $data = $this->request->getPost();
            $subjectModel -> insert([
                'name' => $data['name'],
                'created_by_id' => session()->get('user')['id']
            ]);
            return redirect()->to(base_url('admin/subject/'))->with('success', 'Data berhasil ditambahkan.');
        }

        return view('admin/subject/create', [
            'viewing' => 'subject',
        ]);
    }

    public function delete($id)
    {
        $subjectModel = new SubjectModel();
        $subject = $subjectModel->getById($id);
        if (!$subject) {
            return redirect()->to(base_url('admin/subject/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $subject->delete($id);
        return redirect()->to(base_url('admin/subject/'))->with('success', 'Data berhasil dihapus.');
    }
}
