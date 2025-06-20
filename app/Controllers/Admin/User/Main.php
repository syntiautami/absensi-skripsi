<?php

namespace App\Controllers\Admin\User;

use App\Controllers\BaseController;
use App\Models\ProfileModel;
use App\Models\RoleModel;
use App\Models\UserModel;
use App\Models\UserRoleModel;
class Main extends BaseController
{
    public function index()
    {
        $roleModel = new RoleModel();
        
        $roles = $roleModel->orderBy('alt_name')->findAll();

        return view('admin/user/index', [
            'roles' => $roles,
            'viewing' => 'user',
        ]);
    }
    
    public function users($id)
    {
        $roleModel = new RoleModel();
        $role = $roleModel->where('id',$id)->first();
        if (!$role) {
            return redirect()->to(base_url('admin/users/'))->with('error', 'Data tidak ditemukan.');
        }
        
        $userRoleModel = new UserRoleModel();
        $userRoles = $userRoleModel-> getByRoleId($id);

        return view('admin/user/users', [
            'user_roles' => $userRoles,
            'role' => $role,
            'viewing' => 'user',
        ]);
    }
    public function edit_user($role_id, $id)
    {
        $roleModel = new RoleModel();
        $role = $roleModel->where('id',$role_id)->first();
        if (!$role) {
            return redirect()->to(base_url('admin/users/'))->with('error', 'Data tidak ditemukan.');
        }
        $roles = $roleModel->orderBy('alt_name')->findAll();

        $userModel = new UserModel();
        $user = $userModel->where('id',$id)-> first();

        if ($this->request->getMethod() == 'POST') {
            dd($this->request->getPost());
        }

        if (!$user) {
            return redirect()->to(base_url('admin/users/'.$role_id.'/'))->with('error', 'Data tidak ditemukan.');
        }

        return view('admin/user/edit', [
            'role' => $role,
            'roles' => $roles,
            'user' => $user,
            'viewing' => 'user',
        ]);
    }

    public function edit_profile($role_id, $id)
    {
        $roleModel = new RoleModel();
        $role = $roleModel->where('id',$role_id)->first();
        if (!$role) {
            return redirect()->to(base_url('admin/users/'))->with('error', 'Data tidak ditemukan.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('id',$id)-> first();

        if (!$user) {
            return redirect()->to(base_url('admin/users/'.$role_id.'/'))->with('error', 'Data tidak ditemukan.');
        }

        return view('admin/user/profile', [
            'role' => $role,
            'user' => $user,
            'viewing' => 'user',
        ]);
    }

    public function edit_additional($role_id, $id)
    {
        $roleModel = new RoleModel();
        $role = $roleModel->where('id',$role_id)->first();
        if (!$role) {
            return redirect()->to(base_url('admin/users/'))->with('error', 'Data tidak ditemukan.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('id',$id)-> first();

        if (!$user) {
            return redirect()->to(base_url('admin/users/'.$role_id.'/'))->with('error', 'Data tidak ditemukan.');
        }

        return view('admin/user/additional', [
            'role' => $role,
            'user' => $user,
            'viewing' => 'user',
        ]);
    }
}
