<?php

namespace App\Controllers\Teacher;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Main extends BaseController
{
    public function index()
    {
        return view('teacher/home', [
            'viewing' => 'dashboard'
        ]);
    }
}
