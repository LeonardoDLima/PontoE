<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\PontoSearch;
use app\models\Ponto;

class PontoController extends Controller
{
    public function actionIndex()
    {
        $userId = Yii::$app->user->id;

        $datetime = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $hoje = $datetime->format('Y-m-d');
        $agora = $datetime->format('H:i:s');

        // Busca ou cria registro do ponto para hoje
        $registro = Ponto::findOne(['id_usuario' => $userId, 'data' => $hoje]);

        if (!$registro) {
            $registro = new Ponto([
                'id_usuario' => $userId,
                'data' => $hoje
            ]);
            $registro->save(false);
        }

        if (Yii::$app->request->post('bater')) {
            $finalizar = Yii::$app->request->post('finalizar', '0');

            $campo = null;

            // Identificar o próximo campo vazio na sequência correta
            if (!$registro->entrada1) $campo = 'entrada1';
            elseif (!$registro->saida1) $campo = 'saida1';
            elseif (!$registro->entrada2) $campo = 'entrada2';
            elseif (!$registro->saida2) $campo = 'saida2';
            elseif (!$registro->entrada_extra) $campo = 'entrada_extra';
            elseif (!$registro->saida_extra) $campo = 'saida_extra';

            if ($campo) {
                $registro->$campo = $agora;
                $registro->save(false);

                // Se for saida2 e o usuário escolheu NÃO na modal, finaliza
                if ($campo === 'saida2' && $finalizar === '1') {
                    Yii::$app->session->setFlash('success', 'Ponto do dia finalizado!');
                    return $this->redirect(['index']);
                }

                // Se for saida_extra, finaliza automaticamente
                if ($campo === 'saida_extra') {
                    Yii::$app->session->setFlash('success', 'Ponto do dia finalizado!');
                    return $this->redirect(['index']);
                }

                // Recarrega a página para atualizar os horários no JS
                return $this->redirect(['index']);
            }
        }

        return $this->render('index', [
            'registro' => $registro,
        ]);
    }

    public function actionHistorico()
    {
        $searchModel = new PontoSearch();
        $queryParams = Yii::$app->request->queryParams;

        // Filtra apenas os pontos do usuário logado
        $queryParams['PontoSearch']['id_usuario'] = Yii::$app->user->id;

        $dataProvider = $searchModel->search($queryParams);

        return $this->render('historico', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEditarHorario()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;

        if (!$request->isPost) {
            return ['sucesso' => false, 'mensagem' => 'Requisição inválida'];
        }

        $id = $request->post('id');
        $campo = $request->post('campo');
        $valor = $request->post('valor');

        $campos_validos = ['entrada1', 'saida1', 'entrada2', 'saida2', 'entrada_extra', 'saida_extra'];
        if (!in_array($campo, $campos_validos)) {
            return ['sucesso' => false, 'mensagem' => 'Campo inválido'];
        }

        $ponto = Ponto::findOne($id);
        if (!$ponto) {
            return ['sucesso' => false, 'mensagem' => 'Registro não encontrado'];
        }

        $ponto->$campo = $valor ?: null;

        if ($ponto->save(false)) {
            return ['sucesso' => true];
        } else {
            return ['sucesso' => false, 'mensagem' => 'Erro ao salvar horário'];
        }
    }

    public function actionExcluirHorario()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;

        if (!$request->isPost) {
            return ['sucesso' => false, 'mensagem' => 'Requisição inválida'];
        }

        $id = $request->post('id');
        $campo = $request->post('campo');

        $campos_validos = ['entrada1', 'saida1', 'entrada2', 'saida2', 'entrada_extra', 'saida_extra'];
        if (!in_array($campo, $campos_validos)) {
            return ['sucesso' => false, 'mensagem' => 'Campo inválido'];
        }

        $ponto = Ponto::findOne($id);
        if (!$ponto) {
            return ['sucesso' => false, 'mensagem' => 'Registro não encontrado'];
        }

        $ponto->$campo = null;

        if ($ponto->save(false)) {
            return ['sucesso' => true];
        } else {
            return ['sucesso' => false, 'mensagem' => 'Erro ao excluir horário'];
        }
    }
}

