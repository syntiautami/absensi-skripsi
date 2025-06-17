<?php

namespace App\Controllers\Admin\Report;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Subject extends BaseController
{
    public function index()
    {
        return view('admin/report/subject/index', [
            'viewing' => 'report-subject',
        ]);
    }
}
