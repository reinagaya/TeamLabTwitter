<?php

use Phalcon\Mvc\Controller;

class followController extends Controller
{
    public function indexAction()
    {
        // すべてのフォローデータを取得
        $follows = Follows::find();
        $this->view->follows = $follows;
    }

    public function newfollowAction()
    {
        // すべてのユーザーのidと名前を配列に保存
        $users = Users::find("role = 'users'");
        $this->view->followees =$users;
    }

    public function setfollowAction()
    {
        // Postされた情報がない場合
        if (!$this->request->isPost()) {

            // フォローページに飛ぶ
            $this->dispatcher->forward([
                'controller' => 'follow',
                'action' => 'newfollow'
            ]);

            return;
        }

        // Usersをインスタンス化
        $follow = new Follows();
        
        // 各データを書き込む
        $auth = $this->session->get("auth");
        $follow->follower_id = $auth["id"];

        $follow->followee_id = $this->request->getPost("followee");

        if (!$follow->save()) {
            
            // エラーメッセージを表示
            foreach ($follow->getMessages() as $message) {
                $this->flash->error($message);
            }

            // フォローページに飛ぶ
            $this->dispatcher->forward([
                'controller' => 'follow',
                'action' => 'newfollow'
            ]);

            return;
        }

        // ツイート画面に飛ぶ
        $this->response->redirect("tweet");
    }
}