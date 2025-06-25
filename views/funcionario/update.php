<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Funcionario $model */

$this->title = 'Atualizar Funcionário';
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', ['model' => $model]) ?>
