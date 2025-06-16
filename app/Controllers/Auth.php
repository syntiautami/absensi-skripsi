<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserRoleModel;
use CodeIgniter\Controller;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function attemptLogin()
    {
        $userModel = new UserModel();
        $login = $this->request->getPost('login'); // username atau email
        $password = $this->request->getPost('password');

        $user = $userModel
            ->where('username', $login)
            ->orWhere('email', $login)
            ->first();

            
        if ($user && password_verify($password, $user['password'])) {
            $userRoleModel = new UserRoleModel();
            $user_roles = $userRoleModel->getByUserId($user['id']);
            
            session()->set([
                'user' => $user,
                'logged_in' => true,
                'role' => 'teacher',
            ]);
            return redirect()->to('/teacher'); // ganti sesuai kebutuhan
        }

        return redirect()->back()->with('error', 'Username/email atau password salah');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
