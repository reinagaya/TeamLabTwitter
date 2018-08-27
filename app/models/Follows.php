<?php

class Follows extends \Phalcon\Mvc\Model
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
    public $follower_id;

    /**
     *
     * @var integer
     */
    public $followee_id;

    // セーブ前のバリデーション
    public function beforeSave()
    {
        // 完全一致するデータを探す
        $val_follower = "follower_id = '" . $this->follower_id . "'";
        $val_followee = "followee_id = '" . $this->followee_id . "'";
        $exist = $this->find($val_follower . " and " . $val_followee);

        // 検索数が０でなかったらエラー
        if (count($exist) !== 0) {
            echo "you already follow up this user<br>";
            return false;
        }

        return true;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("twitter_db");
        $this->setSource("follows");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'follows';
    }
}
