<?php

use Phalcon\Mvc\Model;

class Users extends Model
{
    // 登録情報
    public $id = NULL;
    public $name = NULL;
    public $email = NULL;

    // データをセットする
    public function setData($set_name, $set_email)
    {
        $this->name = $set_name;
        $this->email = $set_email;
    }

    // データをDBに送る

    public function sendData()
    {
        echo $this->name . " " . $this->email;

        // データを保存し、エラーをチェックする
        $success = $this->save(
            [
                "name"  => $this->name,
                "email" => $this->email,
            ]
        );

        if ($success) {
            echo "Thanks for registering!";
        } else {
            echo "Sorry, the following problems were generated: ";

            $messages = $this->getMessages();

            foreach ($messages as $message) {
                echo $message->getMessage(), "<br/>";
            }
        }
    }
}