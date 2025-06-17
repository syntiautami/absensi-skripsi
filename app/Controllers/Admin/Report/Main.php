<?php

namespace App\Controllers\Admin\Report;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Main extends BaseController
{
    public function index()
    {
        return view('admin/report/index', [
            'viewing' => 'report',
        ]);
    }
}
