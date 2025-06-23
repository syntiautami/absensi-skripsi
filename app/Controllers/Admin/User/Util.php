<?php

namespace App\Controllers\Admin\User;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Util extends BaseController
{
    public function user_check($slug)
    {
        $value = $this->request->getPost($slug); // ambil value sesuai slug
        $user_id = $this->request->getPost('user_id');

        if (!in_array($slug, ['username', 'email'])) {
            return $this->response->setJSON(false); // slug ga valid
        }

        log_message('debug', "Remote check {$slug}={$value}, user_id={$user_id}");

        $userModel = new UserModel();

        $exists = $userModel
            ->where($slug, $value)
            ->where('id !=', $user_id)
            ->first();

        return $this->response->setJSON($exists ? false : true);
    }
}
