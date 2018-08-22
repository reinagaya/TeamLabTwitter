<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

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

        return $this->validate($validator);
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
