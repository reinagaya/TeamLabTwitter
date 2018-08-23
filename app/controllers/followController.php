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

    }

    public function setfollowAction()
    {

    }
}