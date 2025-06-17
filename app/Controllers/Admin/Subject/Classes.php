<?php

namespace App\Controllers\Admin\Subject;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Classes extends BaseController
{
    public function index()
    {
        return view('admin/subject/classes/index', [
            'viewing' => 'class-subject',
        ]);
    }
}
