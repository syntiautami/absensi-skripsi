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

        if (!$user) {
            return redirect()->to(base_url('admin/users/'.$role_id.'/'))->with('error', 'Data tidak ditemukan.');
        }

        if ($this->request->getMethod() == 'POST') {
            $data = $this->request->getPost();
            $updateData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'username' => $data['username'],
                'email' => $data['email'],
            ];
            if (!empty($data['password'])){
                $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                $updateData['confirm_password'] = password_hash($data['confirm_password'], PASSWORD_DEFAULT);
            }
            $userModel = new UserModel();
            $userModel->update($id, $updateData);
            if (session()->get('user')['id'] == $id) {
                session()->set('user', $userModel->getLoginDataById($id));
            }

            return redirect()->to(base_url('admin/users/'.$role_id.'/edit/'.$id.'/user/'))->with('success', 'Data berhasil diupdate.');
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

        $profileModel = new ProfileModel();
        $profile = $profileModel-> getByUserId($id);

        if ($this->request->getMethod() == 'POST') {
            $data = $this->request->getPost();
            $file = $this->request->getFile('photo');
            $updateData = [
                'gender' => $data['gender'],
                'address' => $data['address'],
                'religion' => $data['religion'],
                'father_name' => $data['father_name'],
                'mother_name' => $data['mother_name'],
                'parent_email' => $data['parent_email'],
            ];
            if ($file->isValid() && !$file->hasMoved()) {
                $folderPath = FCPATH . 'assets/img/users/' . $user['id'] . '/';
                // Bikin folder kalau belum ada
                if (!is_dir($folderPath)) {
                    mkdir($folderPath, 0755, true);
                }
                $newName = $file->getRandomName();
                $photoProfile = 'assets/img/users/'.$user['id'].'/'. $newName;
                $updateData['profile_photo'] = $photoProfile;
                $destination = $folderPath . $newName;
                $imageType = exif_imagetype($file->getTempName());
                if ($imageType == IMAGETYPE_JPEG) {
                    $image = imagecreatefromjpeg($file->getTempName());
                    // Simpan ulang dengan quality 75 (bisa disesuaikan)
                    imagejpeg($image, $destination, 75);
                    imagedestroy($image);
                } elseif ($imageType == IMAGETYPE_PNG) {
                    $image = imagecreatefrompng($file->getTempName());
                    imagepng($image, $destination, 6); // compression level 0-9
                    imagedestroy($image);
                } else {
                    // Simpan biasa kalau bukan jpeg/png
                    $file->move(WRITEPATH . 'uploads/', $newName);
                }
            }
            $profileModel->update($profile['id'], $updateData);
            if (session()->get('user')['id'] == $id) {
                session()->set('user', $userModel->getLoginDataById($id));
            }
            return redirect()->to(base_url('admin/users/'.$role_id.'/edit/'.$id.'/profile/'))->with('success', 'Data berhasil diupdate.');
        }

        return view('admin/user/profile', [
            'profile' => $profile,
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
