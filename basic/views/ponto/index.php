<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Bater Ponto';

$diaAtual = Yii::$app->formatter->asDate($registro['data'] ?? date('Y-m-d'), 'php:d/m/Y');

function calcularHoras($registro) {
    $totalSegundos = 0;

    $pausa = function($inicio, $fim) {
        if ($inicio && $fim) {
            $inicioTS = strtotime($inicio);
            $fimTS = strtotime($fim);
            return max(0, $fimTS - $inicioTS);
        }
        return 0;
    };

    $totalSegundos += $pausa($registro['entrada1'], $registro['saida1']);
    $totalSegundos += $pausa($registro['entrada2'], $registro['saida2']);

    $horas = floor($totalSegundos / 3600);
    $minutos = floor(($totalSegundos % 3600) / 60);

    return sprintf('%02d:%02d', $horas, $minutos);
}

$totalHoras = calcularHoras($registro);
?>

<h1>Bater Ponto</h1>

<p>Usuário: <strong><?= Html::encode(Yii::$app->user->identity->nome) ?></strong></p>

<h4>Dia <?= Html::encode($diaAtual) ?></h4>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Data</th>
            <th>Entrada 1</th>
            <th>Saída 1</th>
            <th>Entrada 2</th>
            <th>Saída 2</th>
            <th>Entrada Extra</th>
            <th>Saída Extra</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= $registro['data'] ?? '-' ?></td>
            <td><?= $registro['entrada1'] ?? '-' ?></td>
            <td><?= $registro['saida1'] ?? '-' ?></td>
            <td><?= $registro['entrada2'] ?? '-' ?></td>
            <td><?= $registro['saida2'] ?? '-' ?></td>
            <td><?= $registro['entrada_extra'] ?? '-' ?></td>
            <td><?= $registro['saida_extra'] ?? '-' ?></td>
        </tr>
    </tbody>
</table>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<?php
// Verificar se já finalizou
$finalizado = ($registro['saida_extra']) || ($registro['saida2'] && (!$registro['entrada_extra'] && !$registro['saida_extra']) && Yii::$app->session->hasFlash('success') && Yii::$app->session->getFlash('success') === 'Ponto do dia finalizado!');
?>

<?php if (!$finalizado): ?>
    <?php $form = ActiveForm::begin(); ?>
    <?= Html::hiddenInput('finalizar', '0', ['id' => 'inputFinalizar']) ?>
    <?= Html::submitButton('Bater', [
        'class' => 'btn btn-primary',
        'name' => 'bater',
        'value' => '1'
    ]) ?>
    <?php ActiveForm::end(); ?>
<?php endif; ?>

<div class="modal fade" id="modalHoraExtra" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Hora Extra</h5>
      </div>
      <div class="modal-body">
        <p>Deseja registrar hora extra?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="btnNaoHoraExtra" data-bs-dismiss="modal">Não</button>
        <button type="button" class="btn btn-primary" id="btnSimHoraExtra" data-bs-dismiss="modal">Sim</button>
      </div>
    </div>
  </div>
</div>

<div class="card" style="padding: 15px; border: 1px solid #ccc; border-radius: 8px; margin-bottom: 20px;">
    <strong>Total de horas trabalhadas no dia:</strong> <?= $totalHoras ?>
</div>

<?php
// Garantir que os valores estejam sempre como string e reflitam a atualização após cada batida
$entrada1 = $registro['entrada1'] ?? '';
$saida1 = $registro['saida1'] ?? '';
$entrada2 = $registro['entrada2'] ?? '';
$saida2 = $registro['saida2'] ?? '';
$entradaExtra = $registro['entrada_extra'] ?? '';
$saidaExtra = $registro['saida_extra'] ?? '';
?>

<?php
$script = <<<JS
$(document).ready(function() {
    let entrada1 = "{$entrada1}";
    let saida1 = "{$saida1}";
    let entrada2 = "{$entrada2}";
    let saida2 = "{$saida2}";
    let entradaExtra = "{$entradaExtra}";
    let saidaExtra = "{$saidaExtra}";

    $('form').on('submit', function(e) {
        // Se estiver batendo saida2 e entrada_extra ainda não foi preenchida
        if (entrada1 && saida1 && entrada2 && !saida2) {
            e.preventDefault();

            $('#modalHoraExtra').modal('show');

            $('#btnSimHoraExtra').off('click').on('click', function() {
                $('#inputFinalizar').val('0');
                $('form').off('submit').submit();
            });

            $('#btnNaoHoraExtra').off('click').on('click', function() {
                $('#inputFinalizar').val('1');
                $('form').off('submit').submit();
            });
        }
    });
});
JS;

$this->registerJs($script);
?>
