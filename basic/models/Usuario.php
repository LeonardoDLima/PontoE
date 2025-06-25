<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Usuario extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'usuario';
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null) {}

    public static function findByEmail($email)
    {
        return self::findOne(['email' => $email]);
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->senha);
    }

    public function getId()
    {
        return $this->id;
    }
    public function getUsername()
{
    return $this->nome; // ou $this->email
}

    public function getAuthKey() {}
    public function validateAuthKey($authKey) {}
}
