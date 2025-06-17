<?php

namespace App\Controllers\Admin\Classes;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Main extends BaseController
{
    public function index()
    {
        return view('admin/user/index', [
            'viewing' => 'classes',
        ]);
    }
}
