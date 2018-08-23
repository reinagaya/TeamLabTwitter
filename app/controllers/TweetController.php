<?php

use Phalcon\Mvc\Controller;

class TweetController extends Controller
{
    public function indexAction()
    {
        // 自分のツイートを取得
        $auth = $this->session->get("auth");
        $tweets = TWEETS::find("user_id = '". $auth["id"] . "'");

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
                $this->flash->error($message);
            }

            // ツイートページに飛ぶ
            $this->response->redirect("tweet");

            return;
        }

        // ツイートページに飛ぶ
        $this->response->redirect("tweet");
    }
}