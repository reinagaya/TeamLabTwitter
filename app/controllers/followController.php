<?php

use Phalcon\Mvc\Controller;
use Phalcon\Db\Column;

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

    public function unfollowAction()
    {
        // 自分のツイートを取得
        $auth = $this->session->get("auth");

        // フォロー用のクエリをバインド
        $folcondition = "follower_id = :id:";

        // 強制的にintに変換
        $type = array("id" => Column::BIND_PARAM_INT);

        // クエリの実行
        $followees = Follows::find([
            $folcondition,
            "bind" => [
                "id" => $auth["id"]
            ],
            "bindTypes" => $type
        ]);

       // 取得するユーザー
       $ids = array();

       // ユーザー用のクエリをバインド
       $userscondition = "";
       $i = (int)0;
       foreach ($followees as $followee) {
           array_push($ids , (int)$followee->followee_id);

           $userscondition .= " id = ?" . $i. " or";
           $i++;
       }

       $userscondition = substr($userscondition, 0, strlen($userscondition) - 3);

       // クエリの実行
       $users = Users::find([
           $userscondition,
           "bind" => $ids,
           "bindTypes" => $type
       ]);

       $this->view->followees = $users;
    }

    public function deletefollowAction()
    {   
        // Postされた情報がない場合
        if (!$this->request->isPost()) {

            // アンフォローページに飛ぶ
            $this->dispatcher->forward([
                'controller' => 'follow',
                'action' => 'unfollow'
            ]);

            return;
        }

        // Usersをインスタンス化
        $follow = new Follows();
        
        // 各データを書き込む
        $auth = $this->session->get("auth");

        // フォロー用のクエリをバインド
        $condition = "follower_id = :follower_id: and followee_id = :followee_id:";

        // 強制的にintに変換
        $type = array(
            "follower_id" => Column::BIND_PARAM_INT,
            "followee_id" => Column::BIND_PARAM_INT
        );

        // クエリの実行
        $follow = Follows::find([
            $condition,
            "bind" => [
                "follower_id" => $auth["id"],
                "followee_id" => $this->request->getPost("followee")
            ],
            "bindTypes" => $type
        ]);

        if (!$follow->delete()) {
            
            // エラーメッセージを表示
            foreach ($follow->getMessages() as $message) {
                $this->flash->error($message);
            }

            // フォローページに飛ぶ
            $this->dispatcher->forward([
                'controller' => 'follow',
                'action' => 'unfollow'
            ]);

            return;
        }

        // ツイート画面に飛ぶ
        $this->response->redirect("tweet");
    }
}