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

        $user = $userModel->getLoginData($login);
            
        if ($user && password_verify($password, $user['password'])) {
            $userRoleModel = new UserRoleModel();
            $user_roles = $userRoleModel->getByUserId($user['id']);
            session()->set([
                'user' => $user,
                'roles' => array_column($user_roles, 'name'),
            ]);

            if (count($user_roles) > 1){
                return redirect()->to(base_url('role/'));
            }
            $role = $user_roles[0];
            if ($role['name'] == 'student') {
                return redirect()->back()->with('error', 'Akun siswa tidak diizinkan login.');
            }
            session()->set('role', $role['name']);
            return redirect()->to(base_url($role['name'] . '/'));
        }

        return redirect()->back()->with('error', 'Username/email atau password salah');
    }

    public function chooseRole()
    {
        $user = session()->get('user');
        $userRoleModel = new UserRoleModel();
        $roles = $userRoleModel->getByUserId($user['id']);

        $data = [
            'roles' => $roles,
        ];
        return view('auth/role', $data);
    }

    public function setRole($role)
    {
        $roles = session('roles') ?? [];
        if (in_array($role, $roles)) {
            session()->set('role', $role);
            return redirect()->to(base_url($role . '/'));
        }
        return redirect()->to('/')->with('error', 'Role tidak valid.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }
}
