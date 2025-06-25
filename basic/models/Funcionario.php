<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Funcionario extends ActiveRecord
{
    public static function tableName()
    {
        return 'funcionario';
    }

    public function rules()
    {
        return [
            [['id_usuario', 'nome', 'cpf', 'senha'], 'required'],
            [['id_usuario'], 'integer'],
            [['nome'], 'string', 'max' => 100],
            [['cpf'], 'string', 'max' => 20],
            [['senha'], 'string', 'max' => 255],
            [['cpf'], 'unique'],
            [['id_usuario'], 'exist', 'targetClass' => Usuario::class, 'targetAttribute' => ['id_usuario' => 'id']],
        ];
    }

    public function getUsuario()
    {
        return $this->hasOne(Usuario::class, ['id' => 'id_usuario']);
    }

    public function validateSenha($senha)
    {
        return password_verify($senha, $this->senha);
    }
}
