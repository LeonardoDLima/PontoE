<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $this->context->action->id === 'create' ? 'Novo Funcionário' : 'Editar Funcionário';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'nome')->textInput() ?>
<?= $form->field($model, 'cpf')->textInput() ?>
<?= $form->field($model, 'senha')->passwordInput(['value' => '']) ?>

<div class="form-group">
    <?= Html::submitButton('Salvar', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
