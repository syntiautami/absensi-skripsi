<?php

namespace App\Controllers\Admin\User;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Main extends BaseController
{
    public function index()
    {
        return view('admin/user/index', [
            'viewing' => 'user',
        ]);
    }
}
