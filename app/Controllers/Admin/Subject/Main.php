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
}
