<?php

use Phalcon\Mvc\Controller;

class TweetController extends Controller
{
    public function indexAction()
    {
        $auth = $this->session->get("auth");
        $tweets =  TWEETS::find($auth["id"]);
        $this->view->tweets = $tweets;
    }
}