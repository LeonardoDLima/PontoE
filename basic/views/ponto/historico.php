<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap5\Modal;


$this->title = 'Histórico de Pontos';

$urlVerificaSenha = Url::to(['site/verificar-senha']);
$urlEditarHorario = Url::to(['ponto/editar-horario']);
$urlExcluirHorario = Url::to(['ponto/excluir-horario']);
$csrf = Yii::$app->request->csrfToken;
?>

<div class="ponto-historico">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Filtros (form) -->
    <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['ponto/historico']]); ?>
    <div class="row">
        <div class="col-md-3"><?= $form->field($searchModel, 'dataInicio')->input('date') ?></div>
        <div class="col-md-3"><?= $form->field($searchModel, 'dataFim')->input('date') ?></div>
        <div class="col-md-3 mt-4"><?= Html::submitButton('Filtrar', ['class' => 'btn btn-primary']) ?></div>
    </div>
    <?php ActiveForm::end(); ?>

    <hr>
    <?php \yii\widgets\Pjax::begin(['id' => 'pjax-grid-pontos']); ?>
    <?= GridView::widget([
    'id' => 'grid-pontos',
    'dataProvider' => $dataProvider,
    
    'columns' => [
        [
            'attribute' => 'data',
            'format' => ['date', 'php:d/m/Y'],
            'label' => 'Data',
        ],
        [
            'attribute' => 'entrada1',
            'label' => 'Entrada 1',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::tag('span', $model->entrada1 ?: '-') . ' ' .
                    Html::button('<i class="bi bi-pencil"></i>', [
                        'class' => 'btn btn-sm btn-outline-primary btn-editar',
                        'data-id' => $model->id,
                        'data-campo' => 'entrada1',
                        'data-valor' => $model->entrada1,
                    ]) . ' ' .
                    Html::button('<i class="bi bi-trash"></i>', [
                        'class' => 'btn btn-sm btn-outline-danger btn-excluir',
                        'data-id' => $model->id,
                        'data-campo' => 'entrada1',
                    ]);
            }
        ],
        [
            'attribute' => 'saida1',
            'label' => 'Saída 1',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::tag('span', $model->saida1 ?: '-') . ' ' .
                    Html::button('<i class="bi bi-pencil"></i>', [
                        'class' => 'btn btn-sm btn-outline-primary btn-editar',
                        'data-id' => $model->id,
                        'data-campo' => 'saida1',
                        'data-valor' => $model->saida1,
                    ]) . ' ' .
                    Html::button('<i class="bi bi-trash"></i>', [
                        'class' => 'btn btn-sm btn-outline-danger btn-excluir',
                        'data-id' => $model->id,
                        'data-campo' => 'saida1',
                    ]);
            }
        ],
        [
            'attribute' => 'entrada2',
            'label' => 'Entrada 2',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::tag('span', $model->entrada2 ?: '-') . ' ' .
                    Html::button('<i class="bi bi-pencil"></i>', [
                        'class' => 'btn btn-sm btn-outline-primary btn-editar',
                        'data-id' => $model->id,
                        'data-campo' => 'entrada2',
                        'data-valor' => $model->entrada2,
                    ]) . ' ' .
                    Html::button('<i class="bi bi-trash"></i>', [
                        'class' => 'btn btn-sm btn-outline-danger btn-excluir',
                        'data-id' => $model->id,
                        'data-campo' => 'entrada2',
                    ]);
            }
        ],
        [
            'attribute' => 'saida2',
            'label' => 'Saída 2',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::tag('span', $model->saida2 ?: '-') . ' ' .
                    Html::button('<i class="bi bi-pencil"></i>', [
                        'class' => 'btn btn-sm btn-outline-primary btn-editar',
                        'data-id' => $model->id,
                        'data-campo' => 'saida2',
                        'data-valor' => $model->saida2,
                    ]) . ' ' .
                    Html::button('<i class="bi bi-trash"></i>', [
                        'class' => 'btn btn-sm btn-outline-danger btn-excluir',
                        'data-id' => $model->id,
                        'data-campo' => 'saida2',
                    ]);
            }
        ],
        [
            'label' => 'Extra',
            'format' => 'raw',
            'value' => function ($model) {
                $entrada = $model->entrada_extra ?: '-';
                $saida = $model->saida_extra ?: '-';

                return "<span><strong>Entrada:</strong> {$entrada}<br><strong>Saída:</strong> {$saida}</span>";
            },
        ],
        [
            'label' => 'Total do Dia',
            'format' => 'raw',
            'value' => function ($model) {
                $somaSegundos = function ($inicio, $fim) {
                    if ($inicio && $fim) {
                        return strtotime($fim) - strtotime($inicio);
                    }
                    return 0;
                };

                $total = 0;
                $total += $somaSegundos($model->entrada1, $model->saida1);
                $total += $somaSegundos($model->entrada2, $model->saida2);
                $total += $somaSegundos($model->entrada_extra, $model->saida_extra);

                $horas = floor($total / 3600);
                $minutos = floor(($total % 3600) / 60);

                return sprintf('%02d:%02d', $horas, $minutos);
            },
        ],
    ],
]); ?>

    <?php \yii\widgets\Pjax::end(); ?>
</div>

<!-- Modal confirmação de senha -->
<div class="modal fade" id="modalSenha" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Confirmação de Senha</h5></div>
      <div class="modal-body">
        <input type="password" id="senhaVerificacao" class="form-control" placeholder="Digite sua senha">
        <div id="senhaErro" class="text-danger mt-2" style="display:none;">Senha incorreta.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="btnCancelarSenha">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnConfirmarSenha">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal edição -->
<?php Modal::begin([
    'title' => 'Editar Horário',
    'id' => 'modalEditar',
    'size' => Modal::SIZE_SMALL,
]); ?>

<?php $form = ActiveForm::begin(['id' => 'formEditar', 'enableAjaxValidation' => false]); ?>

<?= Html::hiddenInput('id', '', ['id' => 'inputEditarId']) ?>
<?= Html::hiddenInput('campo', '', ['id' => 'inputEditarCampo']) ?>

<?= $form->field(new \yii\base\DynamicModel(['valor']), 'valor')
    ->textInput(['type' => 'time', 'id' => 'inputEditarValor', 'name' => 'valor'])
    ->label('Novo horário') ?>

<div class="form-group">
    <?= Html::button('Salvar', ['class' => 'btn btn-success', 'id' => 'btnSalvarEdicao']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>

<!-- Modal exclusão -->
<?php Modal::begin([
    'title' => 'Confirmar Exclusão',
    'id' => 'modalExcluir',
    'size' => Modal::SIZE_SMALL,
]); ?>

<p>Tem certeza que deseja excluir este horário?</p>

<?php $form = ActiveForm::begin(['id' => 'formExcluir', 'enableAjaxValidation' => false]); ?>
<?= Html::hiddenInput('id', '', ['id' => 'inputExcluirId']) ?>
<?= Html::hiddenInput('campo', '', ['id' => 'inputExcluirCampo']) ?>

<div class="form-group">
    <?= Html::button('Excluir', ['class' => 'btn btn-danger', 'id' => 'btnConfirmarExclusao']) ?>
    <?= Html::button('Cancelar', ['class' => 'btn btn-secondary', 'id' => 'btnCancelarExclusao']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>

<?php
$script = <<< JS
let acaoAtual = null; // 'editar' ou 'excluir'
let pontoIdAtual = null;
let campoAtual = null;

function abrirModalSenha(acao, id, campo, valor = '') {
    acaoAtual = acao;
    pontoIdAtual = id;
    campoAtual = campo;
    $('#senhaVerificacao').val('');
    $('#senhaErro').hide();
    $('#modalSenha').modal('show');

    // Se for editar, guarda valor para o modal edição
    if (acao === 'editar') {
        $('#inputEditarId').val(id);
        $('#inputEditarCampo').val(campo);
        $('#inputEditarValor').val(valor || '');
    } else if (acao === 'excluir') {
        $('#inputExcluirId').val(id);
        $('#inputExcluirCampo').val(campo);
    }
}

$(document).ready(function() {
    // Botões editar
    $(document).on('click', '.btn-editar', function() {
        let id = $(this).data('id');
        let campo = $(this).data('campo');
        let valor = $(this).data('valor');
        abrirModalSenha('editar', id, campo, valor);
    });

    // Botões excluir
    $(document).on('click', '.btn-excluir', function() {
        let id = $(this).data('id');
        let campo = $(this).data('campo');
        abrirModalSenha('excluir', id, campo);
    });

    // Cancelar senha
    $('#btnCancelarSenha').on('click', function() {
        $('#modalSenha').modal('hide');
    });

    // Confirmar senha
    $('#btnConfirmarSenha').on('click', function() {
        let senha = $('#senhaVerificacao').val();
        if (!senha) {
            $('#senhaErro').text('Digite a senha').show();
            return;
        }

        $.post('$urlVerificaSenha', {senha: senha, _csrf: '$csrf'}, function(data) {
            if (data.sucesso) {
                $('#modalSenha').modal('hide');

                if (acaoAtual === 'editar') {
                    $('#modalEditar').modal('show');
                } else if (acaoAtual === 'excluir') {
                    $('#modalExcluir').modal('show');
                }
            } else {
                $('#senhaErro').text('Senha incorreta').show();
            }
        });
    });

    // Cancelar exclusão
    $('#btnCancelarExclusao').on('click', function() {
        $('#modalExcluir').modal('hide');
    });

    // Confirmar exclusão (ajax)
    $('#btnConfirmarExclusao').on('click', function() {
        $.post('$urlExcluirHorario', {
            id: pontoIdAtual,
            campo: campoAtual,
            _csrf: '$csrf'
        }, function(data) {
            if (data.sucesso) {
                $('#modalExcluir').modal('hide');
                // Atualiza grid
                $.pjax.reload({container: '#pjax-grid-pontos'});
            } else {
                alert(data.mensagem || 'Erro ao excluir horário.');
            }
        });
    });

    // Salvar edição (ajax)
    $('#btnSalvarEdicao').on('click', function() {
        let novoValor = $('#inputEditarValor').val();
        if (!novoValor) {
            alert('Informe um horário válido.');
            return;
        }

        $.post('$urlEditarHorario', {
            id: pontoIdAtual,
            campo: campoAtual,
            valor: novoValor,
            _csrf: '$csrf'
        }, function(data) {
            if (data.sucesso) {
                $('#modalEditar').modal('hide');
                // Atualiza grid
                $.pjax.reload({container: '#pjax-grid-pontos'});
            } else {
                alert(data.mensagem || 'Erro ao editar horário.');
            }
        });
    });
});
JS;

$this->registerJs($script);
?>