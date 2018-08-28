<?php

use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Role;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

class SecurityPlugin extends Plugin
{
    // アクセス制御リストの設定
    public function getAcl()
    {
        // Aclが未設定の場合
        if (!isset($this->persistent->acl)) {
            $acl = new AclList();

            // アクセスレベルの設定
            $acl->setDefaultAction(Acl::DENY);

            // 各グループのアクセス権限
            $roles = [
                // 管理者
                'admins' => new Role(
                    'Administrators',
                    'Super-User role'
                ),

                // 一般ユーザー
                'users' => new Role(
                    'Users',
                    'Member privileges, granted after sign in.'
                ),

                // ゲスト
                'guests' => new Role(
                    'Guests',
                    'Anyone browsing the site who is not signed in is considered to be a "Guest".'
                )
            ];

            // 各ロールをAclに追加
            foreach ($roles as $role) {
                $acl->addRole($role);
            }

            // 管理者のアクセス制御
            $privateResources = [
                'index'      => ['index'],
                'users'      => ['index'],
                'session'    => ['end'],
                'tweet'      => ['index','posttweet'],
                'follow'     => ['index','newfollow','setfollow','unfollow','deletefollow']
            ];
            foreach ($privateResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            // 一般的な（全員がみられる）アクセス制御
            $publicResources = [
                'users'      => ['new', 'create'],
                'session'    => ['index', 'start'],
            ];
            foreach ($publicResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            // 登録済みのユーザーのアクセス制御
            $usersResources = [
                'index'      => ['index'],
                'session'    => ['end'],
                'tweet'      => ['index','posttweet'],
                'follow'     => ['newfollow','setfollow','unfollow','deletefollow']
            ];

            // 管理者にprivateResourceのすべてのアクセス権を付与
            foreach ($privateResources as $resource => $actions) {
                foreach ($actions as $action){
                    $acl->allow('Administrators', $resource, $action);
                }
            }

            // すべてのユーザーにpublicResourceのすべてのアクセス権を付与
            foreach ($roles as $role) {
                foreach ($publicResources as $resource => $actions) {
                    foreach ($actions as $action){
                        $acl->allow($role->getName(), $resource, $action);
                    }
                }
            }

            // 登録済みユーザーにusersResourceのすべてのアクセス権を付与
            foreach ($usersResources as $resource => $actions) {
                foreach ($actions as $action){
                    $acl->allow('Users', $resource, $action);
                    
                }
            }

            // Aclの設定を投げる
            $this->persistent->acl = $acl;
        }
        return $this->persistent->acl;
    }

    // ディスパッチルート毎に実行する処理
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        // 認証を取得
        $auth = $this->session->get('auth');

        if (!$auth) {
            // 設定されていなかったらゲスト
            $role = 'Guests';
        } else {
            if ($auth['role'] === 'admins') {
                $role = 'Administrators';
            } elseif ($auth['role'] === 'users') {
                $role = 'Users';
            }
        }

        // コントローラーとアクション名を取得
        $controller = $dispatcher->getControllerName();
        $action     = $dispatcher->getActionName();

        $acl = $this->getAcl();

        // アクセスできるか確認
        $allowed = $acl->isAllowed($role, $controller, $action);

        // アクセスできない場合はログイン画面に飛ぶ
        if (!$allowed) {
            $this->flash->error(
                "You don't have access to this module"
            );

            $dispatcher->forward([
                'controller' => 'session',
                'action'     => 'index',
            ]);

            return false;
        }
    }
}