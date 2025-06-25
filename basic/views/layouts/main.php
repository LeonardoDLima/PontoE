<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
$this->registerCssFile('@web/css/tema_a.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
$this->registerCssFile('@web/bootstrap-icons/bootstrap-icons.min.css');

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100 bodynav">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => 'PontoE',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-lg navbar-light',
        ],
    ]);
    
    $menuItems = [
        ['label' => 'Inicio', 'url' => ['/site/index']],
    ];
    
    if (!Yii::$app->user->isGuest) {
        $menuItems[] = [
            'label' => 'Menu',
            'items' => [
                ['label' => 'Histórico', 'url' => ['/ponto/historico'], 'post', ['class' => 'form-inline']],
                ['label' => 'Logout', 'url' => ['/site/logout'], 'post', ['class' => 'form-inline']],

            ],
        ];
        $menuItems[] = '<li class="nav-item">'
            . \yii\helpers\Html::submitButton(
                'Olá ' . Yii::$app->user->identity->nome . '!',
                ['class' => 'nav-link']
            )
            . \yii\helpers\Html::endForm()
            . '</li>';
    } else {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
        $menuItems[] = ['label' => 'Registrar', 'url' => ['/site/register']];
    }
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ml-auto'],
        'items' => $menuItems,
    ]);
    echo Html::button('<i class="bi bi-moon-fill"></i>', [
        'class' => 'btn btn-outline-dark ms-3',
        'id' => 'toggleDarkMode',
        'title' => 'Alternar modo escuro'
    ]);    
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>



<?php
$verificaSenhaUrl = \yii\helpers\Url::to(['/site/verificar-senha']);
$funcionariosUrl = \yii\helpers\Url::to(['/funcionario/index']);
?>

<!-- Modal Senha -->
<div class="modal fade" id="senhaModal" tabindex="-1" aria-labelledby="senhaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Digite sua senha</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <input type="password" id="senhaUsuario" class="form-control" placeholder="Senha do usuário">
        <div id="erroSenha" class="text-danger mt-2" style="display: none;">Senha incorreta</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" onclick="verificarSenha()">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<script>
function solicitarSenhaFuncionario() {
    let modal = new bootstrap.Modal(document.getElementById('senhaModal'));
    document.getElementById('senhaUsuario').value = '';
    document.getElementById('erroSenha').style.display = 'none';
    modal.show();
}

function verificarSenha() {
    let senha = document.getElementById('senhaUsuario').value;

    fetch('<?= $verificaSenhaUrl ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': '<?= Yii::$app->request->getCsrfToken() ?>'
        },
        body: JSON.stringify({ senha: senha })
    })
    .then(response => response.json())
    .then(data => {
        if (data.sucesso) {
            window.location.href = '<?= $funcionariosUrl ?>';
        } else {
            document.getElementById('erroSenha').style.display = 'block';
        }
    });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('toggleDarkMode');
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const savedTheme = localStorage.getItem('darkMode');

    if (savedTheme === 'true' || (!savedTheme && prefersDark)) {
        document.body.classList.add('dark-mode');
    }

    toggle.addEventListener('click', function () {
        document.body.classList.toggle('dark-mode');
        const isDark = document.body.classList.contains('dark-mode');
        localStorage.setItem('darkMode', isDark);
        toggle.innerHTML = isDark ? '<i class="bi bi-sun-fill"></i>' : '<i class="bi bi-moon-fill"></i>';
    });

    // Atualiza ícone conforme estado salvo
    toggle.innerHTML = document.body.classList.contains('dark-mode')
        ? '<i class="bi bi-sun-fill"></i>'
        : '<i class="bi bi-moon-fill"></i>';
});
</script>
<script>
function atualizarTabelasDarkMode() {
    const isDark = document.body.classList.contains('dark-mode');
    document.querySelectorAll('table.table').forEach(tabela => {
        tabela.classList.toggle('table-dark', isDark);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    atualizarTabelasDarkMode(); // aplica ao carregar

    document.getElementById('toggleDarkMode')?.addEventListener('click', function () {
        setTimeout(atualizarTabelasDarkMode, 10); // aplica após toggle
    });
});
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
