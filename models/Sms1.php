<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Sms1 extends Model
{

    public static function Getordersfromdate($data = "2019-07-15") //получение массива неотгруженных href заказов по дате отгрузки от
    {
        $ordersHref = Yii::$app->sklad->getIDOrderPlanned($data);

        if($ordersHref['size']>100){
            $offset=100;
            $ordersHref1 = Yii::$app->sklad->getIDOrderPlanned($data, $offset);
        }

        $result = array_merge($ordersHref, $ordersHref1);
        unset($result['size']);

        return $result;
    }

    public static function Getcodegoods($hrefArray) //получение кодов товаров из заказов
    {
        $goodsHrefs = [];
        if (is_array($hrefArray)){
            foreach ($hrefArray as $val) {
                $goodsHrefs[] = Yii::$app->sklad->getIDGoods($val);
            }
        }

        else {
            $goodsHrefs[] = Yii::$app->sklad->getIDGoods($hrefArray);
        }

        foreach ($goodsHrefs as $val) {
            if($val['codes']!='')
            $goodsCode[] = $val['codes'];
        }

        $goodsCodesNal = array_count_values($goodsCode); //массив кодов товара с их кол-вом*/
        return $goodsCodesNal;
    }

    public static function GetAmauntonSklad($codes) //кол-во товара на складе по коду товара
    {
            $amount = Yii::$app->sklad->getQuantity($code);
        return $amount;
    }


    public static function AddZakaz($goods) //Добавление товаров в заказ поставщику по их кодам
    {
        $idzakaz = Yii::$app->sklad->createZakaz();

        $hrefs = []; // ссылки на товары которые надо добавить в заказ
        if (is_array($goods)){
            foreach ($goods as $code=>$nal){
                $href= Yii::$app->sklad->getHrefgoods($code);
                $add = Yii::$app->sklad->addinzakaz($href, $nal,$idzakaz);
            }
        }
        else {
            "error";
        }

        return $hrefs;
    }

}

