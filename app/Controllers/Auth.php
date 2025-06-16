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
            ]);

            if (count($user_roles) > 1){
                return redirect()->to('role/');
            }
            $role = $user_roles[0];
            session()->set('role', $role);
            return redirect()->to($role.'/');
        }

        return redirect()->back()->with('error', 'Username/email atau password salah');
    }

    public function chooseRole()
    {
        $user = session()->get('user');
        $userRoleModel = new UserRoleModel();
        $roles = $userRoleModel->getByUserId($user['id']); // pastikan ini mengembalikan array list role

        $data = [
            'roles' => $roles,
        ];
        return view('auth/role', $data);
    }

    public function setRole($role)
    {
        session()->set('role', $role);
        return redirect()->to($role . '/');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
