<?php

class UsersController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
        $users = Users::find();
        $this->view->users = $users;
    }

    public function newAction()
    {
    }

    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => 'users',
                'action' => 'new'
            ]);

            return;
        }

        $user = new Users();
        $user->username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $user->password = $this->security->hash($password);
        $user->role = 'users';
        $user->email = $this->request->getPost('email', 'email');
        $user->active = 'Y';

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => 'users',
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success('user was created successfully');

        $this->dispatcher->forward([
            'controller' => 'session',
            'action' => 'index'
        ]);
    }
}