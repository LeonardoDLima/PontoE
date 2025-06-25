<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Ponto extends ActiveRecord
{
    public static function tableName()
    {
        return 'ponto_base';
    }

    public function rules()
    {
        return [
            [['id_usuario', 'data'], 'required'],
            [['id_usuario'], 'integer'],
            [['data'], 'date', 'format' => 'php:Y-m-d'],
            [['entrada1', 'saida1', 'entrada2', 'saida2'], 'safe'],
            [['entrada_extra', 'saida_extra'], 'safe'],
        ];
    }

    public function getUsuario()
    {
        return $this->hasOne(\app\models\User::class, ['id' => 'id_usuario']);
    }
}
