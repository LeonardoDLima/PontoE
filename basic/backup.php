<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ponto;

class PontoSearch extends Ponto
{
    public $dataInicio;
    public $dataFim;

    public function rules()
    {
        return [
            [['id', 'id_usuario'], 'integer'],
            [['data', 'entrada1', 'saida1', 'entrada2', 'saida2', 'entrada_extra', 'saida_extra', 'dataInicio', 'dataFim'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
{
    $query = Ponto::find()->select('*');

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => ['pageSize' => 20],
        'sort' => [
            'defaultOrder' => ['data' => SORT_DESC], // importante!
        ],
    ]);

    $this->load($params);

    if (!$this->validate()) {
        return $dataProvider;
    }

    $query->andFilterWhere(['id_usuario' => $this->id_usuario]);

    if ($this->dataInicio) {
        $query->andWhere(['>=', 'data', $this->dataInicio]);
    }

    if ($this->dataFim) {
        $query->andWhere(['<=', 'data', $this->dataFim]);
    }

    return $dataProvider;
}

}
