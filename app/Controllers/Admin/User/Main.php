<?php

namespace App\Controllers\Admin\User;

use App\Controllers\BaseController;
use App\Models\ProfileModel;
use App\Models\RoleModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
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

    public function create($id)
    {
        $roleModel = new RoleModel();
        $role = $roleModel->where('id',$id)->first();
        if (!$role) {
            return redirect()->to(base_url('admin/users/'))->with('error', 'Data tidak ditemukan.');
        }

        if ($this->request->getMethod() == 'POST') {
            $data = $this->request->getPost();
            $userModel = new UserModel();
            $profileModel = new ProfileModel();
            $userRoleModel = new UserRoleModel();

            $userId = $userModel->insert([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'username'   => $data['username'],
                'password'   => password_hash($data['password'], PASSWORD_DEFAULT),
            ]);
            $profileId = $profileModel->insert([
                'user_id'   => $userId,
                'gender' => $data['gender'],
                'created_by_id' => session()->get('user')['id'],
            ]);

            $userRoleModel -> insert([
                'profile_id' =>$profileId,
                'role_id' => $id,
            ]);

            if ($id == 4) {
                // create object student
                $studentModel = new StudentModel();
                $studentModel-> insert([
                    'profile_id' =>$profileId,
                    'created_by_id' =>session()->get('user')['id'],
                ]);
            }elseif($id == 1){
                // create object teacher
                $teacherModel = new TeacherModel();
                $teacherModel-> insert([
                    'profile_id' =>$profileId,
                    'created_by_id' =>session()->get('user')['id'],
                ]);
            }
            

            return redirect()->to(base_url('admin/users/'.$id.'/'))->with('success', 'Data berhasil ditambahkan.');
        }

        return view('admin/user/create', [
            'role' => $role,
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
        $user = $userModel->getLoginDataById($id);

        $userRoleModel = new UserRoleModel();
        $userRoles = $userRoleModel->getByUserIdRoleIds($id);
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
            $userModel->update($id, $updateData);
            if (session()->get('user')['id'] == $id) {
                session()->set('user', $userModel->getLoginDataById($id));
            }

            $selectedRoles = $this->request->getPost('roles');
            foreach ($selectedRoles as $roleId) {
                // cek apakah user_role sudah ada
                $existing = $userRoleModel
                    ->where('profile_id', $user['profile_id'])
                    ->where('role_id', $roleId)
                    ->first();

                if (!$existing) {
                    // insert kalau belum ada
                    $userRoleModel->insert([
                        'profile_id' => $user['profile_id'],
                        'role_id' => $roleId,
                    ]);
                }
            }
            return redirect()->to(base_url('admin/users/'.$role_id.'/edit/'.$id.'/user/'))->with('success', 'Data berhasil diupdate.');
        }

        return view('admin/user/edit', [
            'role' => $role,
            'roles' => $roles,
            'user' => $user,
            'user_roles' => $userRoles,
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

        $studentModel = new StudentModel();
        $student = $studentModel->getByUserId($id);

        if ($this->request->getMethod() == 'POST') {
            $data = $this->request->getPost();
            $updateData = [
                'nis' => $data['nis'],
                'nisn' => $data['nisn'],
            ];
            $studentModel->update($student['id'], $updateData);
            return redirect()->to(base_url('admin/users/'.$role_id.'/edit/'.$id.'/additional/'))->with('success', 'Data berhasil diupdate.');
        }

        return view('admin/user/additional', [
            'student' => $student,
            'role' => $role,
            'user' => $user,
            'viewing' => 'user',
        ]);
    }
}
