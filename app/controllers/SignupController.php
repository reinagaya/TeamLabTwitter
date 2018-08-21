<?php

use Phalcon\Mvc\Controller;

class SignupController extends Controller
{
    public function indexAction()
    {

    }

    public function registerAction()
    {
        // Postされたデータを受け取る
        $data = $this->request->getPost();

        // Usersをインスタンス化
        $user = new Users;

        $user->setData($data["name"], $data["email"]);

        $user->sendData();
    }
}