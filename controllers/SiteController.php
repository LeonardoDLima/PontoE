<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\Usuario;

class SiteController extends Controller
{
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionRegister()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Usuário registrado com sucesso! Faça login.');
            return $this->redirect(['site/login']);
        }

        return $this->render('register', ['model' => $model]);
    }

    public function actionIndex()
{
    if (Yii::$app->user->isGuest) {
        return $this->redirect(['site/login']);
    }

    $funcionarios = \app\models\Funcionario::find()
        ->where(['id_usuario' => Yii::$app->user->id])
        ->all();

    return $this->render('index', [
        'funcionarios' => $funcionarios,
    ]);
}
public function actionVerificarSenha()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $request = Yii::$app->request;

    $dados = json_decode(file_get_contents('php://input'), true);
    $senha = $dados['senha'] ?? '';
    
    // Aqui faça a validação da senha do usuário logado
    $usuario = Yii::$app->user->identity;

    if (!$usuario || !Yii::$app->security->validatePassword($senha, $usuario->senha)) {
        return ['sucesso' => false];
    }

    return ['sucesso' => true];
}

}
