<?php

namespace app\controllers;

use app\models\Sms1;
use Yii;
use app\models\Zakaz;
use app\models\ZakazSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Component;

/**
 * ZakazController implements the CRUD actions for Zakaz model.
 */
class ZakazController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Zakaz models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ZakazSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Zakaz model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Zakaz model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Zakaz();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Zakaz model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Zakaz model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Zakaz model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Zakaz the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Zakaz::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAddost()
    {
        $data = Zakaz::find()->all();

        foreach ($data as $val){
            $amount = Yii::$app->sklad->getQuantity($val->code); // кол-во на складе*/
            if($amount<$val->kol)
            $codesAddOrder[$val->code] = $val->kol-$amount;
        }
        $result = Sms1::AddZakaz($codesAddOrder);
    }
    public function actionTest2()
    {
        $data = Zakaz::find()->all();

        foreach ($data as $val){
            echo $val->id;
        }

        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    public function actionAdd()
    {
        $hrefs = Sms1::Getordersfromdate("2019-07-29"); // получение списка href заказов

        $codes = SMS1::Getcodegoods($hrefs); // получение кодов товаров из заказа и их кол-вом

        $codesAddOrder = [];
        foreach ($codes as $code => $val) {

            $amount = Yii::$app->sklad->getQuantity($code); // кол-во на складе*/

            if($amount-$val>=0){
                //good
            }
            else{
                $codesAddOrder[$code] = abs($amount-$val);
            }
        }
        echo "<pre>";
        print_r($codesAddOrder);
        echo "</pre>";
        $result = Sms1::AddZakaz($codesAddOrder);
    }
}
