<?php

namespace App\Controllers\Admin\AcademicYear;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Main extends BaseController
{
    public function index()
    {
        return view('admin/academic-year/index', [
            'viewing' => 'academic-year',
        ]);
    }
}
