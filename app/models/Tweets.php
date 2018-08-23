<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\StringLength as StringLength;

class Tweets extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $tweet;

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

    // 型が正しいか確認
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            "tweet",
            new StringLength(
                [
                    "max"            => 140,
                    "messageMaximum" => "tweet is too long",
                ]
            )
        );

        return $this->validate($validator);
    }
}
