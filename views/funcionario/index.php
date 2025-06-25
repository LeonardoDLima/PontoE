<?php
use yii\helpers\Html;

$this->title = 'Funcionários';
?>

<h1>Funcionários</h1>

<p><?= Html::a('Cadastrar Novo Funcionário', ['create'], ['class' => 'btn btn-success']) ?></p>

<ul>
<?php foreach ($funcionarios as $func): ?>
    <li>
        <?= Html::encode($func->nome) ?>
        <?= Html::a('Editar', ['update', 'id' => $func->id]) ?> |
        <?= Html::a('Excluir', ['delete', 'id' => $func->id], [
            'data' => [
                'confirm' => 'Tem certeza que deseja excluir?',
                'method' => 'post',
            ],
        ]) ?>
    </li>
<?php endforeach; ?>
</ul>
