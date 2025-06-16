<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserRoleModel;

class Main extends BaseController
{
    public function index()
    {
        $userRoleModel = new UserRoleModel();
        $user_roles = $userRoleModel->getByUserId(session()->get('user')['id']);
        // foreach ($user_roles as $user_role){
        // }
        // dd($user_roles);
        return view('admin/home');
    }
}
