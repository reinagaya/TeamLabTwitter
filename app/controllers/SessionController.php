<?php

class SessionController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
    }

    private function _registerSession($user)
    {
        // sessionに認証用のidとroleをセット
        $this->session->set(
            'auth',
            [
                'id'   => $user->id,
                'role' => $user->role
            ]
        );
    }

    public function startAction()
    {
        // Postからの情報がある場合
        if ($this->request->isPost()) {
            // ユーザー名とパスワードを取得
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            // DBからユーザー名を探す
            $user = Users::findFirstByUsername($username);

            // ユーザーが見つかったら
            if ($user) {
                // パスワードが一致し、activeがYだったら
                if ($this->security->checkHash($password, $user->password) && $user->active === 'Y') {
                    // 認証をセット
                    $this->_registerSession($user);

                    // Log
                    // $this->flash->success(
                    //     'Welcome ' . $user->username
                    // );

                    // ツイートの画面に飛ぶ
                    return $this->dispatcher->forward([
                        'controller' => 'tweet',
                        'action'     => 'index'
                    ]);
                }
            } else {
                $this->security->hash(rand());
            }

            // エラーログをセット
            $this->flash->error(
                'Wrong username/password'
            );
        }

        // ログイン画面に飛ぶ
        return $this->dispatcher->forward([
            'controller' => 'session',
            'action'     => 'index'
        ]);
    }

    public function endAction()
    {
        // 認証を解除
        $this->session->remove('auth');

        // Log
        $this->flash->success('Goodbye!');

        // ログイン画面に飛ぶ
        return $this->dispatcher->forward(
            [
                'controller' => 'session',
                'action'     => 'index'
            ]
        );
    }
}