<?php

namespace app\models;

use yii\base\Model;
use app\models\Usuario;

class SignupForm extends Model
{
    public $nome;
    public $email;
    public $senha;

    public function rules()
    {
        return [
            [['nome', 'email', 'senha'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => Usuario::class, 'message' => 'Este e-mail já está em uso.'],
            ['senha', 'string', 'min' => 6],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $usuario = new Usuario();
        $usuario->nome = $this->nome;
        $usuario->email = $this->email;
        $usuario->senha = \Yii::$app->security->generatePasswordHash($this->senha);
        return $usuario->save() ? $usuario : null;
    }
}
