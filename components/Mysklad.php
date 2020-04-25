<?php

namespace app\components;

use yii\base\Component;

class Mysklad extends Component
{
    public $token;

    function __construct(){
        parent::__construct();
    }

    private function sendRequest($url){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic ".$this->token,
                "Cache-Control: no-cache",

            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err)
        {
            echo "cURL Error #:" . $err;
        }
        else {

            return $response;
        }

    }

    private function sendRequestPOST($url, $nal, $href){ //не универсальный
        echo "rr";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\r\n  \"quantity\":".$nal.",\r\n  \"price\": 0,\r\n  \"discount\": 0,\r\n  \"vat\": 10,\r\n  \"assortment\": {\r\n    \"meta\": {\r\n      \"href\": \"".$href."\",\r\n     \"type\": \"product\",\r\n      \"mediaType\": \"application/json\"\r\n    }\r\n  }\r\n  \r\n}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic dmljdG9yaWFAcm9nZXJza2xhZDo5MTFWaWN0b3JpYQ==",
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }

    }

    public function getOrdersFromDate ($date){ // получение всех заказов
        $url = "https://online.moysklad.ru/api/remap/1.1/entity/customerorder?filter=moment%3E2018-10-21%2012%3A00%3A00%3Bapplicable%3Dtrue%3B";

        $data = $this->sendRequest($url);
        $result = json_decode($data);


        return $result->rows;
    }

    public function getOrdersBelpostFromDate ($date){ // получение всех заказов со способом доставки почта
        $url = "https://online.moysklad.ru/api/remap/1.1/entity/customerorder?filter=https%3A%2F%2Fonline.moysklad.ru%2Fapi%2Fremap%2F1.1%2Fentity%2Fcustomerorder%2Fmetadata%2Fattributes%2F2aa6111f-21e9-11e8-9109-f8fc000466c8%3Dhttps%3A%2F%2Fonline.moysklad.ru%2Fapi%2Fremap%2F1.1%2Fentity%2Fcustomentity%2Ff44cec20-2156-11e8-9ff4-3150000b9e7a%2F6f12f892-21e8-11e8-9ff4-34e8000488cc%3Bmoment%3E2018-10-15%2012%3A00%3A00%3Bapplicable%3Dtrue%3B";

        $data = $this->sendRequest($url);
        $result = json_decode($data);


        return $result->rows;
    }

    public function getIDOrder ($dates="2018-05-29", $state_id="d4ce5913-9c7b-11e8-9107-504800038560"){ // получение массива id заказов по дате

        $url="https://online.moysklad.ru/api/remap/1.1/entity/customerorder?updatedFrom=".$dates ."%2000:00:00&state.id=".$state_id."&limit=100";

        $file = $this->sendRequest($url);
        $result = json_decode($file);

        $arrayorderID = []; // массив всех id заказов


        foreach ($result->rows as $val) {
            $arrayorderID[] = $val->id;
        }

        if (count($arrayorderID) > 0) {
            return $arrayorderID;
        }

    }

    public function getNumberandTrekorder2 ($orderID){ // получение по id заказа номера и трек кода и отправка сообщения
        $url = ("https://online.moysklad.ru/api/remap/1.1/entity/customerorder/".$orderID);

        $file = $this->sendRequest($url);
        $result = json_decode($file); //Вся информация о заказе

        $data["number"] = substr($result->description,2,9); // номер телефона

        // Проверка указан ли трек код в заказе
        foreach ($result->attributes as $val){
            if($val->name =="Трек-номер"){

                $data["trek"] =$val->value;  // трек код

                if (strlen($data["trek"]) != 13) {
                    $this->error("<span style='color:red;'> Ошибка трек-кода, криворукие, заказ: " . $result->name . "</span>");
                    $data['error'] == false;
                    return $data;
                }
            }
        }
        return $data;
    }

    public function getNumberandTrekorder ($orderID){ // получение по id заказа номера и трек кода и отправка сообщения
        $url = ("https://online.moysklad.ru/api/remap/1.1/entity/customerorder/".$orderID);

        $file = $this->sendRequest($url);
        $result = json_decode($file); //Вся информация о заказе

        // Проверка указан ли трек код в заказе
        foreach ($result->attributes as $val){
            if($val->name =="Трек-номер"){

                $data["trek"] =$val->value;  // трек код

                if (strlen($data["trek"]) != 13) {
                    $this->error("<span style='color:red;'> Ошибка трек-кода, криворукие, заказ: " . $result->name . "</span>");
                    $data["error"] = false;
                }
            }

            if($val->name =="Телефон"){
              /*  $data["number"] = substr($val->value,2,9); // номер телефона*/
                $data["number"] = $val->value; // номер телефона
                if (strlen($data["number"]) != 12) {
                   /* if (strlen($data["number"]) != 9) {*/
                    $this->error("<span style='color:red;'> Ошибка номера телефона, криворукие, заказ: " . $result->name . "</span>");
                    $data["error"] = false;
                }
            }
        }


        return $data;
    }


    public function getNumberandTrekorder3 ($orderID){ // получение по id заказа номера и трек кода и отправка сообщения
        $url = ("https://online.moysklad.ru/api/remap/1.1/entity/customerorder/".$orderID);

        $file = $this->sendRequest($url);
        $result = json_decode($file); //Вся информация о заказе


        /*$data["number"] = substr($result->description,2,9); // номер телефона*/

        // Проверка указан ли трек код в заказе
        foreach ($result->attributes as $val){
            if($val->name =="Трек-номер"){

                if (strlen($data["trek"]) != 13) {
                    $this->error("<span style='color:red;'> Ошибка трек-кода, криворукие, заказ: " . $result->name . "</span>");

                }
                else {
                    $data["trek"] =$val->value;
                }
            }

            if($val->name =="Телефон"){

                if (strlen($val->value) != 11) {
                    $this->error("<span style='color:red;'> Ошибка номера телефона, криворукие, заказ: " . $result->name . "</span>");
                }

                else {
                    $data["number"] =substr($val->value,2,9);  // номер телефона
                }
            }

        }

        if (strlen($data['trek'])==13 && strlen($data['number'])==7){
            $data['error'] == true;
        }
        else {
            $data['error'] == false;
        }

        return $data;
    }



    private function error($error) {
        echo $error."<br>";
    }


    public function getIDOrderPlanned ($data, $offset = 0){ // получение массива неотгруженных id заказов по дате отгрузки от

        $url="https://online.moysklad.ru/api/remap/1.1/entity/customerorder?filter=deliveryPlannedMoment%3E".$data."&filter=applicable%3Dtrue&limit=100&offset=".$offset;
        $file = $this->sendRequest($url);
        $result = json_decode($file);

        $ordersID = []; //массив id заказов
        foreach ($result->rows as $val) {
            if($val->shippedSum == 0){  // если сумма отгруженных товаров  == ноль (поиск неотгруженных товаров
                $ordersID[] = $val->positions->meta->href;
            }
        }
        $ordersID['size'] = $result->meta->size;
        return $ordersID;
    }

    public function getIDGoods ($href){ // получение массива code товаров по ссылке заказа

        $file = $this->sendRequest($href);
        $result = json_decode($file);

        $codes= [];
        foreach ($result->rows as $val) {

            $file = $this->sendRequest($val->assortment->meta->href);
            $result = json_decode($file);

            if( $result->meta->type == "product"){ // если продукт, а не услуга
                $codes['codes'] = $result->code;
                $codes['href'] = $result->meta->href;
            }
        }
        return $codes;
    }

    public function getIDGoods1 ($href){ // получение массива code товаров по ссылке заказа

        $file = $this->sendRequest($href);
        $result = json_decode($file);

        $codes= [];
        foreach ($result->rows as $val) {

            $file = $this->sendRequest($val->assortment->meta->href);
            $result = json_decode($file);
            echo "<pre>";
            print_r($result);
            echo "</pre>";

            if( $result->meta->type == "product"){ // если продукт, а не услуга
                $codes[] = $result->meta->href;
            }
        }
        return $codes;
    }

    public function getQuantity ($code){ // проверка наличия товара

        $url="https://online.moysklad.ru/api/remap/1.1/entity/assortment?filter=code=".$code;

        $file = $this->sendRequest($url);
        $result = json_decode($file);

        foreach ($result->rows as $val) {
            return $val->stock;
        }

    }

    public function addinzakaz ($href, $nal, $id){ //
        $file = $this->sendRequestPOST('https://online.moysklad.ru/api/remap/1.1/entity/purchaseOrder/'.$id.'/positions', $nal, $href);
        $result = json_decode($file);
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        exit();
        

        return $result;
    }
    public function getHrefgoods ($code){ //

        $url="https://online.moysklad.ru/api/remap/1.1/entity/product?filter=code=".$code;
        $file = $this->sendRequest($url);
        $result = json_decode($file);
        $result->rows[0]->meta->href;

        return ($result->rows[0]->meta->href);
    }


    public function createZakaz() //создание заказа поставщику и возрат его номера
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://online.moysklad.ru/api/remap/1.1/entity/purchaseOrder/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\r\n  \"quantity\": 2,\r\n  \"price\": 451,\r\n  \"discount\": 0,\r\n  \"vat\": 10,\r\n   \"agent\": {\r\n         \"meta\": {\r\n          \"href\": \"https://online.moysklad.ru/api/remap/1.1/entity/organization/70fe335f-1e29-11e8-9109-f8fc000a09b6\",\r\n          \"metadataHref\": \"https://online.moysklad.ru/api/remap/1.1/entity/organization/metadata\",\r\n          \"type\": \"organization\",\r\n          \"mediaType\": \"application/json\",\r\n          \"uuidHref\": \"https://online.moysklad.ru/app/#mycompany/edit?id=70fe335f-1e29-11e8-9109-f8fc000a09b6\"\r\n            }\r\n    },\r\n    \"organization\": {\r\n                \"meta\": {\r\n                    \"href\": \"https://online.moysklad.ru/api/remap/1.1/entity/organization/70fe335f-1e29-11e8-9109-f8fc000a09b6\",\r\n                    \"metadataHref\": \"https://online.moysklad.ru/api/remap/1.1/entity/organization/metadata\",\r\n                    \"type\": \"organization\",\r\n                    \"mediaType\": \"application/json\",\r\n                    \"uuidHref\": \"https://online.moysklad.ru/app/#mycompany/edit?id=70fe335f-1e29-11e8-9109-f8fc000a09b6\"\r\n                }\r\n            }\r\n    \r\n}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic dmljdG9yaWFAcm9nZXJza2xhZDo5MTFWaWN0b3JpYQ==",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: 78040f1a-c614-cc3a-1e88-2f417c18979e"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $result = json_decode($response);
            return $result->id;

        }
    }


}
