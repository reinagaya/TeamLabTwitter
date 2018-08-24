<?php

use Phalcon\Mvc\Controller;
use Phalcon\Db\Column;

class TweetController extends Controller
{
    public function indexAction()
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

        // ツイート用のクエリをバインド
        $i = (int)0;
        $tweetscondition = "user_id = ?" . $i;

        // ツイートを取得するユーザー
        $users = array((int)$auth["id"]);

        foreach ($followees as $followee) {
            array_push($users , (int)$followee->followee_id);

            $i++;
            $tweetscondition .= " or user_id = ?" . $i;
        }

        // クエリの実行
        $tweets = TWEETS::find([
            $tweetscondition,
            "bind" => $users,
            "bindTypes" => $type,
            "order" => "created_at"
        ]);

        // エラーメッセージを表示
        $this->flashSession->output();

        // viewにセット
        $this->view->tweets = $tweets;
    }

    public function posttweetAction()
    {
        // Postされた情報がない場合
        if (!$this->request->isPost()) {

            // ツイートページに飛ぶ
            // URLを変更する必要性があるため、forwardではなくredirect
            $this->response->redirect("tweet");

            return;
        }

        // Tweetsをインスタンス化
        $tweet = new Tweets();

        // 各データを書き込む
        $tweet->tweet = $this->request->getPost('tweet');

        $auth = $this->session->get("auth");
        $tweet->user_id = $auth["id"];

        // DBに書き込めなかったら
        if (!$tweet->save()) {
            // エラーメッセージを表示
            foreach ($tweet->getMessages() as $message) {
                $this->flashSession->error($message);
            }

            // ツイートページに飛ぶ
            $this->response->redirect("tweet");

            return;
        }

        // ツイートページに飛ぶ
        $this->response->redirect("tweet");
    }
}