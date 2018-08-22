<?php

class SessionController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
    }

    private function _registerSession($user)
    {
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
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $user = Users::findFirstByUsername($username);

            if ($user) {
                if ($this->security->checkHash($password, $user->password) && $user->active === 'Y') {
                    $this->_registerSession($user);
                    $this->flash->success(
                        'Welcome ' . $user->username
                    );
                    return $this->dispatcher->forward([
                        'controller' => 'index',
                        'action'     => 'index'
                    ]);
                }
            } else {
                $this->security->hash(rand());
            }

            $this->flash->error(
                'Wrong username/password'
            );
        }

        return $this->dispatcher->forward([
            'controller' => 'session',
            'action'     => 'index'
        ]);
    }

    public function endAction()
    {
        $this->session->remove('auth');
        $this->flash->success('Goodbye!');
        return $this->dispatcher->forward(
            [
                'controller' => 'session',
                'action'     => 'index'
            ]
        );
    }
}