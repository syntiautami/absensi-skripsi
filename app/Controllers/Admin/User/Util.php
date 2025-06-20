<?php

namespace App\Controllers\Admin\User;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Util extends BaseController
{
    public function check_username()
    {
        $username = $this->request->getPost('username');
        $user_id = $this->request->getPost('user_id');
        
        $userModel = new UserModel();
        $exists = $userModel
            ->where('username', $username)
            ->where('id !=',$user_id)
            ->first();

        if ($exists) {
            return $this->response->setJSON(false);
        } else {
            return $this->response->setJSON(true);
        }
    }
}
