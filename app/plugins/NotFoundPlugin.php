<?php

use Phalcon\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;
use Phalcon\Mvc\User\Plugin;

class NotFoundPlugin extends Plugin
{
    // 例外が補足された際の処理
    public function beforeException(Event $event, MvcDispatcher $dispatcher, Exception $exception)
    {
        // エラーメッセージの表示
        error_log($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
        
        // exceptionがDispatcherExceptionのインスタンスであるか確認
        if ($exception instanceof DispatcherException) {

            // 例外コードを取得
            switch ($exception->getCode()) {
                // Handlerが見つからない場合
                case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:

                // Actionが見つからない場合
                case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:

                    // errorコントロールに飛ばす
                    $dispatcher->forward([
                        'controller' => 'errors',
                        'action' => 'show404'
                    ]);
                    return false;
            }
        }
        // errorコントロールに飛ばす
        $dispatcher->forward([
            'controller' => 'errors',
            'action'     => 'show500'
        ]);
        return false;
    }
}