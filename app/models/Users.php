<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\StringLength as StringLength;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

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

        // username用のバリデーション　名前の重複を避ける
        $validator->add(
            'username',
            new UniquenessValidator(
                [
                    'model' => $this,
                    'message' => "this username exists"
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

}
