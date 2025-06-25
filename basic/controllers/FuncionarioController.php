<?php

namespace app\controllers;

use Yii;
use app\models\Funcionario;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class FuncionarioController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // usuários autenticados
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $funcionarios = Funcionario::find()
            ->where(['id_usuario' => Yii::$app->user->id])
            ->all();

        return $this->render('index', [
            'funcionarios' => $funcionarios,
        ]);
    }

    public function actionCreate()
    {
        $model = new Funcionario();
        $model->id_usuario = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post())) {
            $model->senha = password_hash($model->senha, PASSWORD_DEFAULT);

            if ($model->save()) {
                $this->criarTabelaPonto($model->id);
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->id_usuario != Yii::$app->user->id) {
            throw new NotFoundHttpException("Funcionário não encontrado.");
        }

        if ($model->load(Yii::$app->request->post())) {
            // Atualizar senha se fornecida
            if (!empty($model->senha)) {
                $model->senha = password_hash($model->senha, PASSWORD_DEFAULT);
            } else {
                $model->senha = $model->getOldAttribute('senha');
            }

            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->id_usuario != Yii::$app->user->id) {
            throw new NotFoundHttpException("Funcionário não encontrado.");
        }

        $this->excluirTabelaPonto($model->id);
        $model->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Funcionario::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException("Funcionário não encontrado.");
    }

    protected function criarTabelaPonto($idFuncionario)
    {
        $tabela = 'ponto_func_' . $idFuncionario;
        Yii::$app->db->createCommand("
            CREATE TABLE IF NOT EXISTS `$tabela` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `data` DATE NOT NULL,
                `entrada1` TIME NULL,
                `saida1` TIME NULL,
                `entrada2` TIME NULL,
                `saida2` TIME NULL,
                `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ")->execute();
    }

    protected function excluirTabelaPonto($idFuncionario)
    {
        $tabela = 'ponto_func_' . $idFuncionario;
        Yii::$app->db->createCommand("DROP TABLE IF EXISTS `$tabela`")->execute();
    }
}
