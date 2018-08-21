<?php

use Phalcon\Mvc\Model;

class Users extends Model
{
    // 登録情報
    public $id = NULL;
    public $name = NULL;
    public $email = NULL;

    // データをセットする
    // public function setData($set_name, $set_email)
    // {
    //     $this->name = $set_name;
    //     $this->email = $set_email;
    // }

    // データをDBに送る
    public function sendData($arr)
    {
        // echo $this->name . " " . $this->email;
        // $data = json_encode(array("name" => $this->name, "email" => $this->email), JSON_UNESCAPED_UNICODE);
        $data = json_encode($arr, JSON_UNESCAPED_UNICODE);
        echo $data. "<br/>";

        // データを保存し、エラーをチェックする
        $success = $this->save(["data" => $data]);

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