<?php

namespace app\controllers;

use Yii;
use yii\helpers\Html;use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\base\Component;
use app\models\Sms;
use yii\widgets\ActiveForm;

use yii\httpclient\Client;


class MainController extends Controller
{


    public function actionIndex()
    {
        //return $this->render('page');
		$form_model = new Sms();

		if($form_model->load(\Yii::$app->request->post())){

		}
		return $this->render('page', compact('form_model'));
    }
	
	public function actionSend()
    {
		$message = Yii::$app->request->post()['Sms']['message'];
		$phone = Yii::$app->request->post()['Sms']['phone'];

		$sendSMS = Yii::$app->sms->sendQuickSMS($phone,$message);

		echo  "<pre>";
		print_r($sendSMS);
		echo "</pre>";


		if ($sendSMS == false){
            echo "<span style='color:red'>Ваш почтовый голубь слишком пьян и сбился с пути</span> ";
		}
		  else{
			echo "Почтовый голубь успешно отправлен";
		  }

    }


    public function actionCreate()
    {

        $dataSMS = Yii::$app->sklad->getIDOrder(); // получение массива id заказов
        $i=0;

        foreach ($dataSMS as $val){   // получение информации с каждого заказа (трек и номер)
            $data = Yii::$app->sklad->getNumberandTrekorder($val);

            if ($data['error'] !== false) {

                $message = "Здравствуйте,трек код вашей посылки:" . $data['trek'] . ", срок хранения: 10 дней, ссылка  для отслеживания: https://webservices.belpost.by/searchRu/" . $data['trek'];

                /* $message = "Здравствуйте, ваш заказ прибыл в отделение почты, срок хранения: 7 дней, трек код " . $data['trek'] . ", ссылка  для отслеживания: https://webservices.belpost.by/searchRu/" . $data['trek'];*/
                $sendSMS = Yii::$app->sms->sendQuickSMS($data["number"], $message); // отправка смс

                if ($sendSMS == false) {
                    echo "<span style='color:red;'> Ваш почтовый голубь слишком пьян и сбился с пути</span>";
                    echo "<br>";
                    echo "Номер телефона:" . $data['number'];
                    echo "<br>";
                    echo "Трек код:" . $data['trek'];
                    echo "<br>";
                    echo "<br>";
                } else {

                    $i = $i + 1;
                    echo $i;
                    echo "<br>";
                    echo "Номер телефона:" . $data['number'];
                    echo "<br>";
                    echo "Трек код:" . $data['trek'];
                    echo "<br>";
                    echo "Почтовый голубь успешно отправлен";
                    echo "<br>";
                    echo "<br>";
                }
            }
        }

    }

    public function actionChange()
    {

        $client = new Client();

        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://app.sms.by/api/v1/sendQuickSms')
            ->setData(['token' => '0fe591a895e4472df4c831452a4dadf6', 'message' => '777','phone' => '375256498244', 'alphaname_id' =>'692'])
            ->send();
        if ($response->isOk) {
            $newUserId = $response->data['id'];
        }


    }
}
