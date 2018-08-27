<?php

class UsersController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
        // すべてのユーザーの取得
        $users = Users::find();
        $this->view->users = $users;
    }

    public function newAction()
    {
    }

    public function createAction()
    {
        // Postされた情報がない場合
        if (!$this->request->isPost()) {

            // 登録ページに飛ぶ
            $this->dispatcher->forward([
                'controller' => 'users',
                'action' => 'new'
            ]);

            return;
        }

        // Usersをインスタンス化
        $user = new Users();
        
        // 各データを書き込む
        $user->username = $this->request->getPost('username');
    
        if (($user->username != "") && (!empty(Users::find(array("username" => $user->username))))) {

            $message = "this username is exist";
            $this->flash->error($message);

            // 登録画面に飛ぶ
            $this->dispatcher->forward([
                'controller' => 'users',
                'action' => 'new'
            ]);
        }
        
        // パスワードはハッシュしてから投げる
        $password = $this->request->getPost('password');
        $user->password = $this->security->hash($password);
        
        // セキュリティー
        $user->role = 'users';

        $user->email = $this->request->getPost('email', 'email');
        $user->active = 'Y';

        
        echo "OK";
        
        // DBに書き込めなかったら
        $user->create();

        if (!$user->save()) {
            
            // エラーメッセージを表示
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            // 登録画面に飛ぶ
            $this->dispatcher->forward([
                'controller' => 'users',
                'action' => 'new'
            ]);

            return;
        }

        echo "OK";

        // Log
        $this->flash->success('user was created successfully');

        // sessionのindexに飛ばす
        $this->dispatcher->forward([
            'controller' => 'session',
            'action' => 'index'
        ]);
    }
}