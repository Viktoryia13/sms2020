<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\base\Component;
use app\models\Sms;

/**
 * SmsController implements the CRUD actions for Sms model.
 */
class SmsController extends Controller
{

    public function actionIndex()
    {
        return $this->render('page');
    }


    public function actionTest1()
    {
        $hrefs = SMS::Getordersfromdate("2019-07-29"); // получение списка href заказов

        $codes = SMS::Getcodegoods($hrefs); // получение кодов товаров из заказа и их кол-вом

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
        $result = SMS::AddZakaz($codesAddOrder);
    }


    public function actionTest()
    {
        $codesAddOrder['02857']='1';
        $result = SMS::AddZakaz($codesAddOrder);
    }



}
