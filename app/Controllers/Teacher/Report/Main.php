<?php

namespace App\Controllers\Teacher\Report;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Main extends BaseController
{
    public function index()
    {
        return view('teacher/report/index', [
            'viewing' => 'report'
        ]);
    }
}
