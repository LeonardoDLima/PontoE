<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Cadastro de Usuário';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'nome')->textInput(['autofocus' => true]) ?>
<?= $form->field($model, 'email')->textInput() ?>
<?= $form->field($model, 'senha')->passwordInput() ?>

<div class="form-group">
    <?= Html::submitButton('Cadastrar', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
