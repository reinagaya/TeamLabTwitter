<?php

$router = $di->getRouter();

// Login　Getメソッドだったときのみ有効
$router->addGet(
    '/login',
    'Session::index'
);

// Login　Postメソッドだったときのみ有効
$router->addPost(
    '/login',
    'Session::start'
);

// Logout Getメソッドだったときのみ有効
$router->addGet(
    '/logout',
    'Session::end'
);

// tweet
$router->add(
    '/',
    'Tweet::index'
);

// $_GET["_url"]からURLを取得
$router->handle();