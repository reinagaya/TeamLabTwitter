<?php

use Phalcon\Di\FactoryDefault;

error_reporting(E_ALL);
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
// define('APP_PATH', realpath('..'). '/app');

try {
    // FactoryDefauktのインスタンス化
    $di = new FactoryDefault();

    // routerの設定
    include APP_PATH . '/config/router.php';
    
    // serviceの呼び出し
    include APP_PATH . '/config/services.php';
     
    $config = $di->getConfig();
    
    // autoloaderの設定
    include APP_PATH . '/config/loader.php';
    
    // Applicationクラスをインスタンス化
    $application = new \Phalcon\Mvc\Application($di);
    // $application = new \Phalcon\Mvc\Application(new \Base\Services($config));

    echo $application->handle()->getContent();
    // echo str_replace(["\n","\r","\t"], '', $application->handle()->getContent());
    // echo $application->handle(!empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : null)->getContent();

} catch (\Exception $e) {
    
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}