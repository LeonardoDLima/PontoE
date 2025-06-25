<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Ponto;

$this->title = 'Página Inicial';

$userId = Yii::$app->user->id;

// Define o intervalo da semana atual (segunda a domingo)
$hoje = new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo'));
$inicioSemana = $hoje->modify(('sunday' === $hoje->format('l')) ? 'this sunday' : 'last sunday')->format('Y-m-d');
$fimSemana = $hoje->format('Y-m-d');

// Cria um DataProvider com os pontos da semana
$dataProvider = new ActiveDataProvider([
    'query' => Ponto::find()
        ->where(['id_usuario' => $userId])
        ->andWhere(['between', 'data', $inicioSemana, $fimSemana])
        ->orderBy(['data' => SORT_ASC]),
    'pagination' => false,
    'sort' => false,
]);
?>

<div class="site-index">
    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Bem-vindo!</h1>
        <p class="lead">Clique abaixo para bater seu ponto.</p>
        <?= Html::a('Bater Ponto', ['ponto/index'], ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <div class="mt-5">
        <h3>Pontos da Semana (<?= date('d/m/Y', strtotime($inicioSemana)) ?> a <?= date('d/m/Y', strtotime($fimSemana)) ?>)</h3>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => false,
            'emptyText' => 'Nenhum ponto registrado nesta semana.',
            'columns' => [
                ['attribute' => 'data', 'format' => ['date', 'php:d/m/Y']],
                ['attribute' => 'entrada1', 'label' => 'Entrada 1'],
                ['attribute' => 'saida1', 'label' => 'Saída 1'],
                ['attribute' => 'entrada2', 'label' => 'Entrada 2'],
                ['attribute' => 'saida2', 'label' => 'Saída 2'],
                ['attribute' => 'entrada_extra', 'label' => 'Entrada Extra'],
                ['attribute' => 'saida_extra', 'label' => 'Saida Extra'],
            ],
        ]) ?>
    </div>
</div>
