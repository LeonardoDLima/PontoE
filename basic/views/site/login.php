<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'PontoE';
?>

<!-- <h1><?= Html::encode($this->title) ?></h1> -->
<div class="box-login">
<h1 class="txt-login">Login</h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'username')->textInput() ?>
<?= $form->field($model, 'password')->passwordInput() ?>

<div class="form-group">
    <?= Html::submitButton('Entrar', ['class' => 'btn btn-primary btn-login']) ?>
</div>
</div>

<?php ActiveForm::end(); ?>
