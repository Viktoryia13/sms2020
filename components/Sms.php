<?php

namespace app\components;

use yii\base\Component;
use yii\httpclient\Client;

class Sms extends Component {

    CONST URL1= 'https://app.sms.by/api/v1/';
    CONST ID = 692;   //alphaname_id
    public $token;



    public function init()
    {

        parent::init();

    }
    public function send($phone, $text){   //
       $message_id=$this->createSMSMessage($text);
        
       return $this->sendSms($message_id[0]->message_id,$phone);
    }

    public function sendQuickSMS($phone, $message){

        $params['message'] = $message;
       /* $params['phone'] = "375".$phone;*/
        $params['phone'] = $phone;
        $params['alphaname_id'] = (integer)self::ID;
        return $this->sendRequest('sendQuickSms', $params);
    }


    public function createSMSMessage1($message) {
        $params['message'] = "Здравствуйте,трек код вашей посылки:".$message. ",ссылка  для отслеживания: https://webservices.belpost.by/searchRu/".$message;
        if (!empty($alphaname_id))
            $params['alphaname_id'] = (integer)self::ID;
        return $this->sendRequest('createSmsMessage', $params);
    }

    public function sendSms($message_id, $phone) {

        $params['message_id'] = (integer)$message_id;
        $params['phone'] = "375".$phone;
       /* $params['phone'] = $phone;/
        return $this->sendRequest('sendSms', $params);
    }

    private function error($error) {
       /* trigger_error("<b>smsUnisender error:</b> $error");*/
        echo "Ошибка ".$error."<br>";
    }

    public function getLimit() {
        return $this->sendRequest('getLimit');
    }


    public function createSMSMessage($message, $alphaname_id=692) {
        $params['message'] = $message;
        if (!empty($alphaname_id))
            $params['alphaname_id'] = (integer)$alphaname_id;	
        return $this->sendRequest('createSmsMessage', $params);
    }

    public function checkSMSMessageStatus($message_id) {
        $params['message_id'] = (integer)$message_id;
        return $this->sendRequest('checkSMSMessageStatus', $params);
    }

    public function getMessagesList() {
        return $this->sendRequest('getMessagesList');
    }


    public function checkSMS($sms_id) {
        $params['sms_id'] = (integer)$sms_id;
        return $this->sendRequest('checkSMS', $params);
    }

     protected function sendRequest($command, $params = array())
     {

         $client = new Client();

         $response = $client->createRequest()
             ->setMethod('GET')
             ->setUrl(self::URL1.$command.'?token='.$this->token)
             ->setData($params)
             ->send();

         $result = strpos($response->content, 'error');

         if($result == true){
             return false;
         }
         else{
             $result=true;
          }

         return $result;

        /* $url = self::URL1.$command.'?token='.$this->token;

         if (!empty($params)) {
             foreach ($params as $k => $v)
                 $url .= '&'.$k.'='.urlencode($v);
         }


         $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        echo  $result = curl_exec($ch);


         curl_close($ch);


         $result = json_decode($result);


         if (isset($result->error)) {
             $this->error($result->error);
             return false;
         }
         else
             return $result;*/
     }

}