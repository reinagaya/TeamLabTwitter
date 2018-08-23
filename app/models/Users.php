<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\StringLength as StringLength;

class Users extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $role;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     *
     * @var string
     */
    public $active;

    /**
     * Validations and business logic
     *
     * @return boolean
     */

    // 型が正しいか確認
    public function validation()
    {
        $validator = new Validation();

        // email用のバリデーションを作成
        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        $validator->add(
            'password',
            new StringLength(
                [
                    'min'            => 5,
                    'messageMinimum' => 'password is too short',
                ]
            )
        );

        return $this->validate($validator);
    }

    // セーブ前のバリデーション
    public function beforeSave()
    {
        // usernameの重複がないか確認
        $existuser = $this->find("username = '". $this->username . "'");
        
        // 検索数が０出なかったらエラー
        if (count($existuser) !== 0) {
            echo "this username exists<br>";
            return false;
        }

        return true;
    }

    // モデルのメソッドの初期化
    public function initialize()
    {
        $this->setSchema("twitter_db");
        $this->setSource("users");
    }

    /**
     * テーブルネームを返す
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * parametersで投げられた条件に一致する記録を照会するのを許可
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * parametersで投げられた条件に一致する最初記録を照会するのを許可
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
